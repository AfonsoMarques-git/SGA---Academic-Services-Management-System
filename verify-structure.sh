#!/usr/bin/env bash
# Quick Start Script - Sistema de Gestão de Serviços Académicos

echo "🚀 Verificação de Estrutura do Projeto SGA"
echo "=========================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check directories
echo "📁 Verificando estrutura de pastas..."
echo ""

directories=(
  "config"
  "core"
  "models"
  "views"
  "public"
  "sql"
  "controllers"
  "routes"
  "views/layouts"
  "views/student"
  "views/staff"
  "views/manager"
  "public/assets"
  "public/uploads"
  "public/uploads/photos"
)

for dir in "${directories[@]}"; do
  if [ -d "$dir" ]; then
    echo -e "${GREEN}✅${NC} $dir"
  else
    echo -e "${RED}❌${NC} $dir (FALTA)"
  fi
done

echo ""
echo "📄 Verificando ficheiros principais..."
echo ""

files=(
  "config/database.php"
  "config/app.php"
  "core/bootstrap.php"
  "core/helpers.php"
  "core/session.php"
  "models/User.php"
  "models/StudentRecord.php"
  "models/Course.php"
  "models/CourseUnit.php"
  "models/EnrollmentRequest.php"
  "models/GradeSheet.php"
  "public/login.php"
  "public/dashboard.php"
  "public/logout.php"
  "public/index.php"
  "public/.htaccess"
  "sql/schema.sql"
  "sql/seeds.sql"
)

for file in "${files[@]}"; do
  if [ -f "$file" ]; then
    echo -e "${GREEN}✅${NC} $file"
  else
    echo -e "${RED}❌${NC} $file (FALTA)"
  fi
done

echo ""
echo "📚 Verificando documentação..."
echo ""

docs=(
  "COMECE-AQUI.md"
  "RESUMO-FINAL.md"
  "XAMPP-LOCAL-CONFIG.md"
  "AJUSTES-XAMPP-LOCAL.md"
  "START-HERE.md"
  "README.md"
  "FASE0-ANALISE.md"
)

for doc in "${docs[@]}"; do
  if [ -f "$doc" ]; then
    echo -e "${GREEN}✅${NC} $doc"
  else
    echo -e "${YELLOW}⚠️${NC}  $doc"
  fi
done

echo ""
echo "=========================================="
echo "🎉 Verificação Concluída!"
echo ""
echo "Próximo passo:"
echo "1. Abra: http://localhost/Aulas%20PHP/Aula1/public/login.php"
echo "2. Login com: joao_silva / password123"
echo "3. Leia: COMECE-AQUI.md para instruções completas"
