with open(r'c:\xampp\htdocs\website-harpa\laravel\resources\views\field\dashboard.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

target_start = '<!-- 3. TO-DO LIST TAB -->'
target_end = '<!-- 4. DISTRIBUTION REPORTS TAB -->'

s_idx = c.find(target_start)
e_idx = c.find(target_end)

if s_idx != -1 and e_idx != -1:
    replacement = """<!-- 3. TO-DO LIST TAB (TRELLO KANBAN BOARD) -->
            <div x-show="activeTab === 'todo'" class="space-y-6">
                <x-trello-board 
                    :todos="$todos" 
                    :canAssign="false" 
                    :storeRoute="route('field.todo.store')" 
                    :showViewToggle="true" 
                />
            </div>

            """
    new_c = c[:s_idx] + replacement + c[e_idx:]
    with open(r'c:\xampp\htdocs\website-harpa\laravel\resources\views\field\dashboard.blade.php', 'w', encoding='utf-8') as f:
        f.write(new_c)
    print('Successfully updated field/dashboard.blade.php with Trello Board!')
else:
    print('Failed to find markers', s_idx, e_idx)
