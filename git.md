# Git Huskeliste – Arvid Kodehode

## Første gang på ny maskin
```bash
git config --global user.name "Arvid Kodehode"
git config --global user.email "arvid.kodehode@gmail.com"

Klon prosjektet fra GitHub
git clone https://github.com/dittbrukernavn/reponavn.git

Lagre og laste opp endringer
git add .
git commit -m "Din melding her"
git push

Hent siste endringer fra GitHub
git pull

Sjekk status
git status

Åpne prosjekt i VS Code
code .

Eksempel på .gitignore
node_modules/
.vscode/
.env
*.log


---

### 🛡️ `.gitignore`
```gitignore
# VS Code settings
.vscode/

# node.js
node_modules/
npm-debug.log

# Miljøfiler
.env

# Mac og systemfiler
.DS_Store
Thumbs.db

# Midlertidige filer
*.log
*.tmp
