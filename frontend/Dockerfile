FROM node:22-alpine

WORKDIR /app

# Copia apenas os arquivos de dependência
COPY package*.json ./

# Instala de forma limpa e sem gerar logs excessivos
RUN npm ci --quiet

# Copia o código e faz o build
COPY . .
RUN npm run build

EXPOSE 3000
# Se for produção, você pode usar um servidor estático como 'serve' ou Nginx
# Mas manteremos o CMD original se você estiver usando o Node para servir
CMD ["npm", "run", "preview"]