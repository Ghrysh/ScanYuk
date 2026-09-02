with open('app/Models/QueueStaff.php', 'r') as f:
    content = f.read()

import re

# Fix the setter block which is completely corrupted
bad_block = """    {
        // Only hash if not already hashed
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }

    public function verifyPassword(string $password): bool
        return Hash::check($password, $this->password);
    }"""

good_block = """    public function setPasswordAttribute($value)
    {
        // Only hash if not already hashed
        if (!preg_match('/^\$2y\$/', $value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }"""

content = content.replace(bad_block, good_block)

with open('app/Models/QueueStaff.php', 'w') as f:
    f.write(content)

