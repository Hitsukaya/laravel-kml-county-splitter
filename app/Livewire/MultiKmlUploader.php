<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MultiKmlUploader extends Component
{
    use WithFileUploads;

    public $kmlFiles = [];
    public $selectedCounties = [];
    public $CountiesfoundPerFile = [];
    public $downloadLinksPerFile = [];
    public $availableCounties = ['VS', 'GL', 'BZ', 'BR'];

    protected $rules = [
        'kmlFiles.*' => 'required|file|mimes:kml,xml',
        'selectedCounties' => 'required|array|min:1',
    ];

    public function updatedKmlFiles()
    {
        $this->validateOnly('kmlFiles.*');

        if (!empty($this->kmlFiles)) {
            $fileNames = array_map(fn($file) => $file->getClientOriginalName(), $this->kmlFiles);
            session()->flash('message', 'Selected files: ' . implode(', ', $fileNames));
        }
    }

    public function process()
    {
        Log::info('Processing multiple KML files');
        Log::info('Selected counties:', $this->selectedCounties);

        $this->reset(['CountiesfoundPerFile', 'downloadLinksPerFile']);

        $this->validate();

        foreach ($this->kmlFiles as $index => $file) {

            $path = $file->getRealPath();

            $xml = simplexml_load_file($path);
            if (!$xml) {
                Log::error("Error loading KML file: {$file->getClientOriginalName()}");
                session()->flash('message', "Error loading file: {$file->getClientOriginalName()}");
                $this->CountiesfoundPerFile[$index] = [];
                $this->downloadLinksPerFile[$index] = [];
                continue;
            }

            $namespaces = $xml->getNamespaces(true);
            if (isset($namespaces[''])) {
                $xml->registerXPathNamespace('k', $namespaces['']);
            } else {
                $xml->registerXPathNamespace('k', 'http://www.opengis.net/kml/2.2');
            }

            $placemarks = $xml->xpath('//k:Placemark') ?: [];
            $totalPlacemarks = count($placemarks);

            if ($totalPlacemarks === 0) {
                $this->CountiesfoundPerFile[$index] = [];
                $this->downloadLinksPerFile[$index] = [];
                continue;
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

                $foundInAnyCounty = false;
                foreach ($this->selectedCounties as $county) {
                    $aliases = $aliasuriCounties[$county] ?? [$county];

                    foreach ($aliases as $alias) {
                        if (
                            stripos($nume, $alias) !== false ||
                            stripos($descriere, $alias) !== false ||
                            stripos($extendedDataString, $alias) !== false
                        ) {
                            $placemarksByCounty[$county][] = $placemark;
                            $rezultate[$county] = ($rezultate[$county] ?? 0) + 1;
                            $foundInAnyCounty = true;
                            break 2;
                        }
                    }
                }

                // if (!$foundInAnyCounty) {

                // }
            }

            $this->CountiesfoundPerFile[$index] = $rezultate;

            $this->downloadLinksPerFile[$index] = [];
            foreach ($rezultate as $county => $count) {
                if (isset($placemarksByCounty[$county])) {
                    $this->downloadLinksPerFile[$index][strtolower($county)] = $this->createFiles(strtolower($county), $placemarksByCounty[$county], $index);
                }
            }
        }

        session()->flash('message', 'Files were processed successfully.');
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

    /**
     * Generates KML and CSV files per county per uploaded file
     * @param string $prefix File name prefix (e.g., 'vs_0')
     * @param array $placemarks List of filtered placemarks
     * @param int $fileIndex Index of current file (used for folder structure)
     * @return array Download URLs for KML and CSV
     */
    protected function createFiles(string $prefix, array $placemarks, int $fileIndex)
    {
        Storage::disk('public')->makeDirectory("kmlmulti/file_{$fileIndex}");
        Storage::disk('public')->makeDirectory("csvmulti/file_{$fileIndex}");

        $kml = new \SimpleXMLElement('<kml xmlns="http://www.opengis.net/kml/2.2"><Document></Document></kml>');

        foreach ($placemarks as $placemark) {
            $domDest = dom_import_simplexml($kml->Document);
            $domSrc = dom_import_simplexml($placemark);
            $domDest->appendChild($domDest->ownerDocument->importNode($domSrc, true));
        }

        $filenameKml = "kmlmulti/file_{$fileIndex}/{$prefix}_" . Str::random(8) . ".kml";
        $kmlContent = $kml->asXML();
        Storage::disk('public')->put($filenameKml, $kmlContent);

        // Construiește CSV-ul
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

        $filenameCsv = "csvmulti/file_{$fileIndex}/{$prefix}_" . Str::random(8) . ".csv";
        Storage::disk('public')->put($filenameCsv, $csvContent);

        return [
            'kml' => url('/storage/' . $filenameKml),
            'csv' => url('/storage/' . $filenameCsv),
        ];
    }

    public function render()
    {
        return view('livewire.multi-kml-uploader', [
            'availableCounties' => $this->availableCounties,
        ]);
    }
}
