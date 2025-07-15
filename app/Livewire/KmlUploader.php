<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class KmlUploader extends Component
{
    use WithFileUploads;

    public $kmlFile;
    public $selectedCounties = [];
    public $Countiesfound  = [];
    public $downloadLinks = [];
    public $availableCounties = ['VS', 'GL', 'BZ', 'BR'];

    public $message = '';
    public $detailedMessage = '';

    protected $rules = [
        'kmlFile' => 'required|file|mimes:kml,xml',
        'selectedCounties' => 'required|array|min:1',
    ];

    public function updatedKmlFile()
    {
        $this->validateOnly('kmlFile');

        if ($this->kmlFile) {
            session()->flash('message', 'Selected file: ' . $this->kmlFile->getClientOriginalName());
        }
    }

    public function process()
    {
        Log::info('process() called');
        Log::info('Selected Counties:', $this->selectedCounties);

        $this->reset(['Countiesfound', 'downloadLinks', 'message', 'detailedMessage']);

        $this->validate();

        $path = $this->kmlFile->getRealPath();

        $xml = simplexml_load_file($path);
        if (!$xml) {
            Log::error('Could not load the KML file.');
            session()->flash('message', 'Error loading KML file.');
            return;
        }

        $namespaces = $xml->getNamespaces(true);
        if (isset($namespaces[''])) {
            $xml->registerXPathNamespace('k', $namespaces['']);
        } else {
            $xml->registerXPathNamespace('k', 'http://www.opengis.net/kml/2.2');
        }

        $placemarks = $xml->xpath('//k:Placemark') ?: [];

        $totalPlacemarks = count($placemarks);
        Log::info('Number of placemarks:', ['count' => $totalPlacemarks]);

        if ($totalPlacemarks === 0) {
            $this->message = 'No placemarks found in the KML file.';
            return;
        }

        $rezultate = [];
        $placemarksByCounty = [];

        $aliasuriCounties = [
            'VS' => ['VS', 'Vaslui'],
            'GL' => ['GL', 'Galati', 'Galați'],
            'BZ' => ['BZ', 'Buzau', 'Buzău'],
            'BR' => ['BR', 'Braila', 'Brăila'],
        ];

        foreach ($placemarks as $placemark) {
            $nume = trim((string) $placemark->name);
            $descriere = trim((string) ($placemark->description ?? ''));

            $extendedDataString = '';
            $extData = $placemark->ExtendedData ?? null;
            if ($extData) {
                $extendedDataString = $this->extractExtendedDataAsString($extData);
            }

            foreach ($this->selectedCounties as $county) {
                $found = false;

                $aliases = $aliasuriCounties[$county] ?? [$county];

                foreach ($aliases as $alias) {
                    if (
                        stripos($nume, $alias) !== false ||
                        stripos($descriere, $alias) !== false ||
                        stripos($extendedDataString, $alias) !== false
                    ) {
                        $placemarksByCounty[$county][] = $placemark;
                        $rezultate[$county] = ($rezultate[$county] ?? 0) + 1;
                        $found = true;
                        break;
                    }
                }

                if ($found) {
                    break;
                }
            }
        }

        Log::info('Found results per county:', $rezultate);

        $this->Countiesfound  = $rezultate;

        foreach ($placemarksByCounty as $county => $placemarks) {
            $this->downloadLinks[strtolower($county)] = $this->createFiles(strtolower($county), $placemarks);
        }

        Log::info('Generated links:', $this->downloadLinks);

        $this->message = $this->generateMessage($totalPlacemarks);

        $details = [];
        foreach ($this->selectedCounties as $county) {
            $count = $rezultate[$county] ?? 0;
            $details[] = "$county: $count | ";
        }
        //$this->detailedMessage = implode(" ", $details);
    }

    protected function generateMessage($totalPlacemarks): string
    {
        return
            "<ul>" .
                "<li><strong>Selected Counties:</strong> " . implode(', ', $this->selectedCounties) . "</li>" .
                "<li><strong>Placemarks:</strong> $totalPlacemarks</li>" .
                "<li><strong>Results:</strong>" . $this->generateLinksHtml() . "</li>" .
                // "<li><strong>Generated links:</strong>" . $this->generateLinksHtml() . "</li>" .
            "</ul>";
    }

    protected function generateLinksHtml()
    {
        if (empty($this->Countiesfound)) {
            return '<em>Nothing found</em>';
        }

        $html = "<ul>";

        foreach ($this->Countiesfound as $county => $count) {
            $html .= "<li>";
            $html .= "<strong>" . strtoupper($county) . "</strong>: $count placemarks";

            if (isset($this->downloadLinks[strtolower($county)])) {
                $links = $this->downloadLinks[strtolower($county)];
                $html .= " — <a href='{$links['kml']}' target='_blank' class='text-blue-600 underline'>KML</a> | ";
                $html .= "<a href='{$links['csv']}' target='_blank' class='text-blue-600 underline'>CSV</a>";
            }

            $html .= "</li>";
        }

        $html .= "</ul>";

        return $html;
    }


    protected function extractExtendedDataAsString($extendedData)
    {
        $text = '';

        if (isset($extendedData->Data)) {
            foreach ($extendedData->Data as $data) {
                $text .= (string) $data->value . ' ';
            }
        }

        if (isset($extendedData->SchemaData)) {
            foreach ($extendedData->SchemaData as $schemaData) {
                foreach ($schemaData->SimpleData as $simpleData) {
                    $text .= (string) $simpleData . ' ';
                }
            }
        }

        return trim($text);
    }

protected function createFiles(string $prefix, array $placemarks)
{
    Storage::disk('public')->makeDirectory('kml');
    Storage::disk('public')->makeDirectory('csv');

    $kml = new \SimpleXMLElement('<kml xmlns="http://www.opengis.net/kml/2.2"><Document></Document></kml>');

    foreach ($placemarks as $placemark) {
        $domDest = dom_import_simplexml($kml->Document);
        $domSrc = dom_import_simplexml($placemark);
        $domDest->appendChild($domDest->ownerDocument->importNode($domSrc, true));
    }

    //$filenameKml = "{$prefix}_" . Str::random(8) . ".kml";
    $filenameKml = "kml/{$prefix}_" . Str::random(8) . ".kml";
    $kmlContent = $kml->asXML();
    Storage::disk('public')->put($filenameKml, $kmlContent);

    $allKeys = [];
    $rows = [];

    foreach ($placemarks as $placemark) {
        $row = [];
        $row['name'] = (string) $placemark->name;
        $row['description'] = (string) ($placemark->description ?? '');

        $extendedData = [];

        if (isset($placemark->ExtendedData)) {
            if (isset($placemark->ExtendedData->SchemaData)) {
                foreach ($placemark->ExtendedData->SchemaData as $schemaData) {
                    foreach ($schemaData->SimpleData as $simpleData) {
                        $key = (string) $simpleData->attributes()->name;
                        $value = (string) $simpleData;
                        $extendedData[$key] = $value;
                        if (!in_array($key, $allKeys)) {
                            $allKeys[] = $key;
                        }
                    }
                }
            }

            if (isset($placemark->ExtendedData->Data)) {
                foreach ($placemark->ExtendedData->Data as $data) {
                    $key = (string) $data->attributes()->name;
                    $value = (string) $data->value;
                    $extendedData[$key] = $value;
                    if (!in_array($key, $allKeys)) {
                        $allKeys[] = $key;
                    }
                }
            }
        }

        foreach ($allKeys as $key) {
            if (!isset($row[$key])) {
                $row[$key] = $extendedData[$key] ?? '';
            }
        }

        $rows[] = $row;
    }

    $csvHandle = fopen('php://temp', 'r+');
    fputcsv($csvHandle, $allKeys);

    foreach ($rows as $row) {
        $line = [];
        foreach ($allKeys as $key) {
            $line[] = $row[$key] ?? '';
        }
        fputcsv($csvHandle, $line);
    }

    rewind($csvHandle);
    $csvContent = stream_get_contents($csvHandle);
    fclose($csvHandle);

    //$filenameCsv = "{$prefix}_" . Str::random(8) . ".csv";
    $filenameCsv = "csv/{$prefix}_" . Str::random(8) . ".csv";
    Storage::disk('public')->put($filenameCsv, $csvContent);


    return [
        //'kml' => url('/storage/' . basename($filenameKml)),
        //'csv' => url('/storage/' . basename($filenameCsv)),
        'kml' => url('/storage/' . $filenameKml),
        'csv' => url('/storage/' . $filenameCsv),
    ];
}

    public function render()
    {
        return view('livewire.kml-uploader');
    }
}
