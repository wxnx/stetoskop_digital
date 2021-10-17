<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-6 py-3 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h3 class="text-center">Details Patient</h3>
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="" value="" />
                    <x-jet-input id="" type="text" class="mt-1 block w-full" wire:model.defer="" autocomplete="" />
                    <x-jet-input-error for="" class="mt-2" />
                </div>
                <div class="py-3">
                    <button class="px-5 sm:rounded-lg bg-yellow-400 text-black-800 font-bold p-4 uppercase border-t border-b border-r" type="submit">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
