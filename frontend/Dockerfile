FROM node:16-alpine

WORKDIR /app

COPY . .

RUN npm install && npm i -g serve

RUN npm run build

EXPOSE 8080

CMD ["serve", "-l", "8080", "dist"]


