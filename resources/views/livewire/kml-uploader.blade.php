<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4 text-center">{{ config('app.name', 'Laravel') }}</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="process" enctype="multipart/form-data" class="space-y-4 py-2">
        <div>
            <label for="kmlFile" class="block font-semibold mb-1">Files KML:</label>
            <input type="file" id="kmlFile" wire:model="kmlFile" accept=".kml,.xml" class="border p-2 w-full rounded-2xl" />
            @error('kmlFile') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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

    @if($message)
        <div class="px-4 py-4 rounded-2xl border border-gray-300 bg-gray-100">
            {{-- <strong>Rezultate:</strong> --}}
            {!! $message !!}
        </div>
    @endif


    {{-- @if($detailedMessage)
        <div class="my-2 px-2 p-2 font-semibold rounded-2xl border border-gray-300 bg-gray-100 font-mono whitespace-pre-wra">
            Selected counties and number of placemarks: {{ $detailedMessage }}
        </div>
    @endif --}}

    {{-- @if (!empty($downloadLinks))
        <div class="mt-4">
            <h3 class="font-semibold">Downloads:</h3>
            <ul>
                @foreach ($downloadLinks as $county => $links)
                    <li>
                        <strong>{{ strtoupper($county) }}:</strong>
                        <a href="{{ $links['kml'] }}" target="_blank" class="text-blue-600 underline">KML</a>
                        <a href="{{ $links['csv'] }}" target="_blank" class="text-blue-600 underline">CSV</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif --}}
</div>
