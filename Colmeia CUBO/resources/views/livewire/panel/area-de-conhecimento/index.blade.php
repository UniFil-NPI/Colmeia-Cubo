<div class="flex flex-col items-center justify-center gap-8">

    @session('success')
        <div id="message" class="absolute left-0 top-0 w-full bg-green-400 py-2 text-center text-neutral-50">

            <div class="relative w-full text-center">
                {{ session('success') }}
                <div class="bar absolute -bottom-3 w-full bg-green-700 py-1"></div>
            </div>

        </div>
    @endsession

    @session('error')
        <div id="message" class="absolute left-0 top-0 w-full bg-red-400 py-2 text-center text-neutral-50">
            <div class="relative w-full text-center">
                {{ session('error') }}
                <div class="bar absolute -bottom-3 w-full bg-red-700 py-1"></div>
            </div>
        </div>
    @endsession

    <div class="flex h-[14vh] w-full items-center justify-center rounded-xl bg-[#F1641F] text-white"
        style="background-image: url({{ asset('background.png') }});">
        <span class="select-none text-3xl font-bold">Gerenciamento de Áreas de Conhecimento</span>
    </div>

    <div class="flex select-none items-center gap-4">
        <div class="flex items-center gap-2">
            <div class="size-8 rounded-lg bg-white p-2 shadow-md"></div>
            <span>Áreas de Conhecimento</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="size-8 rounded-lg bg-neutral-200 p-2 shadow-md"></div>
            <span>Áreas Funcionais</span>
        </div>
    </div>

    @if ($id)
        <nav class="flex w-full select-none" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 rtl:space-x-reverse md:space-x-2">
                <li class="inline-flex items-center">

                    <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">
                        {{ $acs->first()?->curso->name }}
                    </span>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="mx-1 h-3 w-3 text-gray-400 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a draggable="false" href="{{ route('ac.index') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-[#F1641F]">
                            Áreas de Conhecimento
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    @endif

    <div class="grid w-full grid-cols-8 items-center gap-2">
        <div class="col-span-4">
            <x-input wire:model.live="search" placeholder="Pesquisar por área de conhecimento..."
                icon="magnifying-glass" />
        </div>

        <div class="col-span-3 flex">
            {{ $acs->links('vendor.livewire.pagination-links') }}
        </div>

        <a href="{{ route('ac.create') }}" draggable="false" wire:navigate.hover
            class="col-span-1 rounded-lg bg-gray-700 px-4 py-2 text-center text-sm font-medium text-white transition-all duration-100 ease-linear hover:bg-gray-600">
            Nova AC
        </a>

    </div>


    <div class="grid w-full grid-cols-6 rounded-xl bg-white text-center" x-data="{ menu: null, line: null }">

        <div class="col-span-6 grid grid-cols-6 border-b-2 py-4">
            <div class="col-span-1 font-semibold">
                Nome
            </div>

            <div class="col-span-1 font-semibold">
                Descrição
            </div>

            <div class="col-span-1 font-semibold">
                Cor
            </div>

            <div class="col-span-1 font-semibold">
                Duração
            </div>

            <div class="col-span-1 font-semibold">
                Status
            </div>

            <div class="col-span-1 font-semibold">
                Ação
            </div>
        </div>

        @foreach ($acs as $index => $ac)
            <div class="relative col-span-6 grid grid-cols-6 items-center border-b-2">

                <div class="relative col-span-6 grid grid-cols-6 items-center">

                    @if ($ac->areasFuncionais->count() > 0)
                        <x-feathericon-chevron-up
                            class="min-w-6 absolute right-5 top-1/2 z-10 w-6 -translate-y-1/2 cursor-pointer"
                            x-show="line == {{ $ac->id }}"
                            x-on:click="line == {{ $ac->id }} ? line = null : line = {{ $ac->id }}" />

                        <x-feathericon-chevron-down
                            class="min-w-6 absolute right-5 top-1/2 z-10 w-6 -translate-y-1/2 cursor-pointer"
                            x-show="line != {{ $ac->id }}"
                            x-on:click="line == {{ $ac->id }} ? line = null : line = {{ $ac->id }}" />
                    @endif


                    <div class="col-span-1 py-4">
                        <a draggable="false" href="{{ route('af.show', $ac->id) }}">
                            {{ $ac->name }}
                        </a>
                    </div>

                    <div class="col-span-1 py-4">
                        <p class="line-clamp-2">{{ $ac->description }}</p>
                    </div>

                    <div class="col-span-1 flex items-center justify-center py-4">
                        <div class="size-10 cursor-pointer rounded-full p-2 transition-all duration-100 ease-linear hover:scale-[1.02]"
                            style="background-color: {{ $ac->color }}; box-shadow: 0px 1px 10px 1px {{ $ac->color }}88">

                        </div>
                    </div>

                    <div class="col-span-1 py-4">
                        @php
                            $duration = 0;

                            foreach ($ac->areasFuncionais as $af) {
                                foreach ($af->unidadesCurriculares as $uc) {
                                    $duration += $uc->duration;
                                }
                            }
                        @endphp

                        {{ $duration }} Horas
                    </div>

                    <div class="col-span-1 py-4">
                        <label class="inline-flex cursor-pointer items-center" wire:loading.remove
                            wire:target='toggleStatus({{ $ac->id }})'>
                            <input type="checkbox" id="checkbox-{{ $ac->id }}" class="peer sr-only"
                                name="{{ $ac->id }}" wire:click='toggleStatus({{ $ac->id }})'
                                {{ $ac->status ? 'checked' : '' }}>
                            <div
                                class="peer relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-[#ff8b00] peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#ff8b0066] rtl:peer-checked:after:-translate-x-full">
                            </div>
                        </label>

                        <div wire:loading wire:target='toggleStatus({{ $ac->id }})'
                            class="ms-2 h-6 w-6 animate-spin rounded-full border-2 border-gray-300 border-t-[#ff8b00]">
                        </div>
                    </div>

                    <div class="relative col-span-1 flex items-center justify-center gap-4">

                        <x-feathericon-trash-2 wire:click.prevent="delete({{ $ac->id }})"
                            class="min-w-6 w-6 cursor-pointer text-red-400" />

                        <a draggable="false" href="{{ route('af.create-with-id', $ac->id) }}" class="relative"
                            x-data="{ tooltip: false }">
                            <x-feathericon-plus-circle class="min-w-6 w-6 cursor-pointer text-green-500"
                                x-on:mouseover="tooltip = true" x-on:mouseleave="tooltip = false" />

                            <div x-show="tooltip" x-transition:enter="transition ease-linear duration-75"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-linear duration-75"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="absolute bottom-full left-1/2 z-20 mb-3 -translate-x-1/2 whitespace-nowrap rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-medium text-gray-800 shadow-[0px_12px_30px_-4px_rgba(16,24,40,0.08);] transition-opacity duration-300">
                                <span
                                    class="absolute -bottom-1.5 left-1/2 -z-10 h-3 w-3 -translate-x-1/2 rotate-45 border-b border-r border-gray-300 bg-white"></span>
                                Nova Área Funcional
                            </div>
                        </a>

                        <a draggable="false" href="{{ route('ac.edit', $ac->id) }}">
                            <x-feathericon-edit class="min-w-6 w-6 cursor-pointer text-amber-500" />
                        </a>

                    </div>


                </div>

                <div class="col-span-6 grid grid-cols-6" x-show="line == {{ $ac->id }}" x-collapse>

                    @foreach ($ac->areasFuncionais as $index => $af)
                        <div class="col-span-6 grid grid-cols-6 bg-neutral-200 py-4 text-center">

                            <div>
                                {{ $af->name }}
                            </div>

                            <div>
                            </div>


                            <div>
                                @php
                                    $afDuration = 0;

                                    foreach ($af->unidadesCurriculares as $uc) {
                                        $afDuration += $uc->duration;
                                    }
                                @endphp

                                {{ $afDuration }} Horas
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</div>