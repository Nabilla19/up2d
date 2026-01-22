#!/usr/bin/env python3
"""
Fix Asset Pages - Search and Reset Buttons
Fixes duplicate reset buttons and ensures proper variable usage
"""

import re
import os

# Define pages and their variable names
pages_config = {
    'gardu_induk': {'var': 'q', 'module': 'Gardu_induk'},
    'gardu_hubung': {'var': 'search', 'module': 'Gardu_hubung'},
    'gh_cell': {'var': 'search', 'module': 'Gh_cell'},
    'pembangkit': {'var': 'search', 'module': 'Pembangkit'},
    'kit_cell': {'var': 'search', 'module': 'Kit_cell'},
    'pemutus': {'var': 'search', 'module': 'Pemutus'},
    'gi_cell': {'var': 'search', 'module': 'Gi_cell'},
}

base_path = 'application/views'

for page_name, config in pages_config.items():
    file_path = f"{base_path}/{page_name}/vw_{page_name}.php"
    
    if not os.path.exists(file_path):
        print(f"❌ File not found: {file_path}")
        continue
    
    print(f"📝 Processing: {file_path}")
    
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    var_name = config['var']
    module_name = config['module']
    
    # Pattern to find and replace reset button section
    # Looking for the pattern after "Cari</button>" until "</form>"
    
    # Create the correct reset button code
    reset_button = f'''                        <button type="submit" class="btn btn-sm btn-primary ms-2">Cari</button>
                        <?php if (!empty(${var_name})): ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="window.location.replace('<?= base_url('{module_name}/index?per_page=' . (int)$per_page); ?>')">Reset</button>
                        <?php endif; ?>
                    </form>'''
    
    # Find the search form section and replace
    # This regex finds from "Cari</button>" to "</form>"
    pattern = r'(<button type="submit"[^>]*>Cari</button>)(.*?)(</form>)'
    
    def replacer(match):
        return reset_button
    
    new_content = re.sub(pattern, replacer, content, flags=re.DOTALL)
    
    # Backup original
    backup_path = f"{file_path}.backup_fix"
    with open(backup_path, 'w', encoding='utf-8') as f:
        f.write(content)
    
    # Write new content
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(new_content)
    
    print(f"  ✅ Fixed! Backup saved to {backup_path}")

print("\n🎉 All asset pages fixed!")
