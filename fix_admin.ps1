$path = 'c:\xampp\htdocs\donation-web\frontend\src\views\dashboard\Admin.vue'
$content = Get-Content $path -Raw

# 1. Ganti allTasks -> filteredTasks di v-for
$content = $content.Replace('v-for="task in allTasks"', 'v-for="task in filteredTasks"')

Set-Content $path $content -NoNewline
Write-Host "Done: allTasks -> filteredTasks"
