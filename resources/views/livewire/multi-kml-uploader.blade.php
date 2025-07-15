<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4 text-center">{{ config('app.name', 'Laravel') }} <br>MAX 5 FILES</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="process" enctype="multipart/form-data" class="space-y-4 py-2">
        <div>
            <label for="kmlFiles" class="block font-semibold mb-1">Files KML (max 5):</label>
            <input type="file" id="kmlFiles" wire:model="kmlFiles" multiple accept=".kml,.xml" class="border p-2 w-full rounded-2xl" />
            @error('kmlFiles.*') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Select counties for separation:</label>
            <div class="flex flex-wrap gap-3">
                @foreach ($availableCounties as $county)
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" wire:model="selectedCounties" value="{{ $county }}" class="appearance-none w-5 h-5 rounded-full border border-gray-400 checked:bg-blue-600 checked:border-blue-600 transition duration-200" />
                        <span>{{ $county }}</span>
                    </label>
                @endforeach
            </div>
            @error('selectedCounties') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
           Generate files
        </button>
    </form>

    @if (!empty($CountiesfoundPerFile))
        <h3 class="text-lg font-semibold mb-4">Results per file:</h3>

        @foreach ($CountiesfoundPerFile as $fileIndex => $results)
            <div class="mb-6 border p-4 rounded-2xl bg-gray-50 py-2 px-2">
                <h4 class="font-semibold mb-2">Files {{ $fileIndex + 1 }}:</h4>

                @if (empty($results))
                    <p class="italic text-gray-600">No placemarks or counties found.</p>
                @else
                    <ul class="space-y-1">
                        @foreach ($results as $county => $count)
                            <li>
                                <strong>{{ strtoupper($county) }}</strong>: {{ $count }} placemarks

                                @if(isset($downloadLinksPerFile[$fileIndex][strtolower($county)]))
                                    <div class="ml-4 mt-1">
                                        <a href="{{ $downloadLinksPerFile[$fileIndex][strtolower($county)]['kml'] }}" target="_blank" class="text-blue-600">
                                            Download KML
                                        </a>
                                        <a href="{{ $downloadLinksPerFile[$fileIndex][strtolower($county)]['csv'] }}" target="_blank" class="text-blue-600">
                                            Download CSV
                                        </a>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    @endif
</div>
