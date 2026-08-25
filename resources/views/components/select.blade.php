{{--
    <x-select> — root komponen, setara <Select> di React version (SelectPrimitive.Root).

    Props:
    - name       : nama field untuk hidden input (submit form biasa / non-Livewire).
                   Mode multiple otomatis menambahkan suffix "[]".
    - value      : value awal yang terpilih (array jika multiple)
    - size       : default | xs | sm | lg  (diteruskan ke trigger & item via scope Alpine)
    - disabled   : boolean
    - placeholder: teks saat belum ada value terpilih
    - searchable : boolean — aktifkan mode combobox (bisa mengetik untuk memfilter item)
    - multiple   : boolean — pilih banyak item sekaligus (klik item tidak menutup dropdown)

    State (Alpine) yang tersedia untuk child (trigger, content, item):
    - open, value, selectedLabel, size, disabled, searchable, multiple, query, active, items
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
    'multiple' => false,
])

<div x-data="{
    open: false,
    value: @js($multiple ? ($value ?? []) : $value),
    _label: '',
    get selectedLabel() {
        if (!this.multiple) return this._label
        if (!this.value || !this.value.length) return ''
        return this.value.map(v => this.items[v]).filter(Boolean).join(', ')
    },
    size: @js($size),
    disabled: @js($disabled),
    placeholder: @js($placeholder),
    searchable: @js($searchable),
    multiple: @js($multiple),
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
        if (this.multiple) {
            const idx = this.value.indexOf(val)
            if (idx === -1) this.value.push(val)
            else this.value.splice(idx, 1)
            this.query = ''
            this.active = null
            this.$dispatch('select-change', { name: @js($name), value: [...this.value] })
            return
        }
        this.value = val
        this._label = label
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
    @if ($name && ! $multiple)
        <input type="hidden" name="{{ $name }}" x-ref="hiddenInput" :value="value" @if($required) required @endif />
    @elseif ($name && $multiple)
        <template x-for="v in value" :key="`hidden-${v}`">
            <input type="hidden" :name="@js($name) + '[]'" :value="v" />
        </template>
    @endif

    {{ $slot }}
</div>
