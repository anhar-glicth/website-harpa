with open(r'c:\xampp\htdocs\website-harpa\laravel\resources\views\dashboard.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

# Marker before absensi/todo widget
marker_start = '<!-- Dashboard Content -->'
# Marker before stats
marker_end = '<!-- Stats -->'

idx_content = c.find(marker_start)
idx_stats = c.find(marker_end)

if idx_content != -1 and idx_stats != -1:
    # Extract the header title part after marker_start
    title_start = c.find('<div class="space-y-2">', idx_content)
    title_end = c.find('</div>', title_start) + 6
    title_block = c[title_start:title_end]

    # Find the absensi card block inside the current code
    absen_start = c.find('{{-- Absensi GPS Card --}}', idx_content)
    absen_card_start = c.rfind('<div x-data="{ showAbsenForm: false }"', 0, absen_start + 50)
    absen_card_end = c.find('{{-- To-Do List Card --}}', absen_card_start)
    absen_card_end = c.rfind('</div>', 0, absen_card_end) + 6
    absen_block = c[absen_card_start:absen_card_end]

    new_section = f"""{title_block}

            {{-- Absensi GPS (Hanya untuk Staf Internal) --}}
            @if(!Auth::user()->isUser())
            <div class="space-y-4">
                {absen_block}
            </div>
            @endif

            {{-- Papan Tugas / To-Do List Trello (Tersedia untuk Semua Role) --}}
            <div class="space-y-4">
                <x-trello-board 
                    :todos="$todos" 
                    :canAssign="false" 
                    :storeRoute="route('todo.store')" 
                    :showViewToggle="true" 
                />
            </div>

            """

    # Replace from title_start to idx_stats
    new_c = c[:title_start] + new_section + c[idx_stats:]
    with open(r'c:\xampp\htdocs\website-harpa\laravel\resources\views\dashboard.blade.php', 'w', encoding='utf-8') as f:
        f.write(new_c)
    print('Successfully updated dashboard.blade.php with Trello Board!')
else:
    print('Markers not found', idx_content, idx_stats)
