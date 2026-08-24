{{--
    <x-select> — root komponen, setara <Select> di React version (SelectPrimitive.Root).

    Props:
    - name       : nama field untuk hidden input (submit form biasa / non-Livewire)
    - value      : value awal yang terpilih
    - size       : default | xs | sm | lg  (diteruskan ke trigger & item via scope Alpine)
    - disabled   : boolean
    - placeholder: teks saat belum ada value terpilih
    - searchable : boolean — aktifkan mode combobox (bisa mengetik untuk memfilter item)

    State (Alpine) yang tersedia untuk child (trigger, content, item):
    - open, value, selectedLabel, size, disabled, searchable, query, active, items
    - select(value, label), toggle(), close(), choose(val), moveActive(dir)
    - scrollUp, scrollDown, updateScroll(el)  -> untuk scroll button di content
--}}
@props([
    'name' => null,
    'value' => null,
    'size' => 'lg',
    'disabled' => false,
    'placeholder' => 'Select...',
    'searchable' => false,
    'required' => false,
])

<div x-data="{
    open: false,
    value: @js($value),
    selectedLabel: '',
    size: @js($size),
    disabled: @js($disabled),
    placeholder: @js($placeholder),
    searchable: @js($searchable),
    query: '',
    active: null,
    items: {},
    scrollUp: false,
    scrollDown: false,
    updateScroll(el) {
        if (!el) return;
        this.scrollUp = el.scrollTop > 1;
        this.scrollDown = el.scrollTop + el.clientHeight < el.scrollHeight - 1;
    },

    select(val, label) {
        if (this.disabled) return
        this.value = val
        this.selectedLabel = label
        this.open = false
        this.query = ''
        this.active = null
        this.$refs.hiddenInput && (this.$refs.hiddenInput.value = val)
        this.$dispatch('select-change', { name: @js($name), value: val })
    },
    choose(val) {
        if (this.items[val] !== undefined) this.select(val, this.items[val])
    },
    moveActive(dir) {
        const box = this.$refs.content
        if (!box) return
        const els = [...box.querySelectorAll('[role=option]')].filter(el => el.offsetParent !== null)
        if (!els.length) return
        let idx = els.findIndex(el => el.dataset.value == this.active)
        idx = idx === -1 ? (dir > 0 ? 0 : els.length - 1) : Math.max(0, Math.min(els.length - 1, idx + dir))
        this.active = els[idx].dataset.value
        els[idx].scrollIntoView({ block: 'nearest' })
    },
    toggle() {
        if (this.disabled) return
        this.open = !this.open
    },
    close() {
        this.open = false
    },
}" @keydown.escape.window="close()" @click.outside="close()" {{ $attributes->class(['relative w-full']) }}>
    @if ($name)
        <input type="hidden" name="{{ $name }}" x-ref="hiddenInput" :value="value" @if($required) required @endif />
    @endif

    {{ $slot }}
</div>
