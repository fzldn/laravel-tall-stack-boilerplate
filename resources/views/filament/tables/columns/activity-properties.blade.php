@php
    $hasNewValues = (bool) data_get($getState(), 'attributes');
    $hasOldValues = (bool) data_get($getState(), 'old');
    $showValue = fn($value) => $value === null ? str('null')->wrapHtmlTag('span', ['class' => 'text-gray-500'])->toHtmlString() : $value;
@endphp

<div class="w-full rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10 hidden md:block">
    <table
        class="w-full table-auto divide-y divide-gray-200 dark:divide-white/5"
    >
        <thead>
            <tr>
                <th
                    scope="col"
                    class="w-1/5 px-3 py-2 text-start text-sm font-medium text-gray-700 dark:text-gray-200"
                >
                    {{ __('Attribute') }}
                </th>

                @foreach (collect($getState() ?? [])->sort() as $type => $value)
                    <th
                        scope="col"
                        class="w-2/5 px-3 py-2 text-start text-sm font-medium text-gray-700 dark:text-gray-200"
                    >
                        {{ match ($type) {
                            'attributes' => __('New Value'),
                            'old' => __('Old Value'),
                        } }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody
            class="divide-y divide-gray-200 font-mono text-xs dark:divide-white/5 md:text-sm sm:leading-6"
        >
            @forelse (data_get($getState(), 'attributes', data_get($getState(), 'old', [])) as $key => $value)
                <tr
                    class="divide-x divide-gray-200 dark:divide-white/5 rtl:divide-x-reverse"
                >
                    <td class="px-3 py-1.5">
                        {{ $key }}
                    </td>

                    @if ($hasNewValues)
                        <td class="px-3 py-1.5">
                            {{ $showValue(data_get($getState(), "attributes.{$key}")) }}
                        </td>
                    @endif

                    @if ($hasOldValues)
                        <td class="px-3 py-1.5">
                            {{ $showValue(data_get($getState(), "old.{$key}")) }}
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td
                        colspan="2"
                        class="px-3 py-2 text-center font-sans text-sm text-gray-400 dark:text-gray-500"
                    >
                        {{ $getPlaceholder() }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="w-full text-sm md:hidden flex flex-col gap-y-4 -mb-4">
    @foreach (collect($getState() ?? [])->sort() as $type => $value)
        <div>
            <div class="mb-3">
                <strong>{{ match ($type) {
                    'attributes' => __('New Value'),
                    'old' => __('Old Value'),
                } }}</strong>
            </div>
            <div class="bg-white border-t border-t-gray-950/5 dark:bg-white/5 dark:ring-white/10 p-4 flex flex-col gap-y-3 -mx-[calc(1rem-1px)]">
                @forelse (data_get($getState(), $type, []) as $key => $value)
                    <div class="flex flex-col gap-y-2 text-xs">
                        <strong>{{ $key }}</strong>
                        <span>{{ $showValue($value) }}</span>
                    </div>
                @empty
                    <div>
                        {{ $getPlaceholder() }}
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
