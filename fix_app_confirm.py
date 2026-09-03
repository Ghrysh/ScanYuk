import re

with open('resources/views/layouts/app.blade.php', 'r') as f:
    content = f.read()

old_exec = """        execute() {
            if (this.targetForm) {
                this.targetForm.submit();
            }
            this.show = false;
        }"""
        
new_exec = """        execute() {
            if (this.targetForm) {
                if (typeof this.targetForm === 'string') {
                    window.location.href = this.targetForm;
                } else if (typeof this.targetForm === 'function') {
                    this.targetForm();
                } else {
                    this.targetForm.submit();
                }
            }
            this.show = false;
        }"""

if old_exec in content:
    content = content.replace(old_exec, new_exec)
    with open('resources/views/layouts/app.blade.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND")
