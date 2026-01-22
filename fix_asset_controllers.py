#!/usr/bin/env python3
"""
Fix Asset Controllers - Clear Session Search on Reset
Adds logic to clear session search when per_page is set but search param is not
"""

import re
import os

# Define controllers and their search variable names
controllers_config = {
    'Gardu_induk': {'var': 'q', 'session_key': 'gardu_induk_q'},
    'Gardu_hubung': {'var': 'search', 'session_key': 'gardu_hubung_search'},
    'Gh_cell': {'var': 'search', 'session_key': 'gh_cell_search'},
    'Pembangkit': {'var': 'search', 'session_key': 'pembangkit_search'},
    'Kit_cell': {'var': 'search', 'session_key': 'kit_cell_search'},
    'Pemutus': {'var': 'search', 'session_key': 'pemutus_search'},
    'Gi_cell': {'var': 'search', 'session_key': 'gi_cell_search'},
}

base_path = 'application/controllers'

for controller_name, config in controllers_config.items():
    file_path = f"{base_path}/{controller_name}.php"
    
    if not os.path.exists(file_path):
        print(f"❌ File not found: {file_path}")
        continue
    
    print(f"📝 Processing: {file_path}")
    
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    var_name = config['var']
    session_key = config['session_key']
    
    # Pattern to find the search logic
    # Looking for: if ($this->input->get('var') !== null) { ... } else { ... }
    
    # Create the fixed search logic
    old_pattern = rf"(if \(\$this->input->get\('{var_name}'\) !== null\) \{{[^}}]+\$this->session->set_userdata\('{session_key}', \${var_name}\);[\s\r\n]+)\}} else \{{[\s\r\n]+\${var_name} = \$this->session->userdata\('{session_key}'\)[^;]+;[\s\r\n]+\}}"
    
    new_logic = rf"""\1}} elseif ($this->input->get('per_page') !== null && $this->input->get('{var_name}') === null) {{
            // If per_page is set but {var_name} is not, clear the session search (this is a reset)
            $this->session->unset_userdata('{session_key}');
            ${var_name} = '';
        }} else {{
            ${var_name} = $this->session->userdata('{session_key}') ?? '';
        }}"""
    
    # Try to apply the fix
    new_content = re.sub(old_pattern, new_logic, content, flags=re.MULTILINE | re.DOTALL)
    
    if new_content == content:
        print(f"  ⚠️  Pattern not found or already fixed")
        continue
    
    # Backup original
    backup_path = f"{file_path}.backup_session_fix"
    with open(backup_path, 'w', encoding='utf-8') as f:
        f.write(content)
    
    # Write new content
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(new_content)
    
    print(f"  ✅ Fixed! Backup saved to {backup_path}")

print("\n🎉 All asset controllers fixed!")
