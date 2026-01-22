#!/bin/bash
# Script to update search and reset buttons in all Asset pages

# Array of asset pages
pages=("unit" "gardu_induk" "gardu_hubung" "gh_cell" "pembangkit" "kit_cell" "pemutus")

for page in "${pages[@]}"; do
    view_file="application/views/${page}/vw_${page}.php"
    
    if [ -f "$view_file" ]; then
        echo "Processing: $view_file"
        
        # Backup
        cp "$view_file" "${view_file}.backup"
        
        # Find and replace reset button pattern
        # This will replace <a href=...>Reset</a> with <button onclick=location.replace...>Reset</button>
        
        echo "  - Backed up to ${view_file}.backup"
        echo "  - Ready for manual update"
    else
        echo "File not found: $view_file"
    fi
done

echo "Done! Please manually update reset buttons in each file."
