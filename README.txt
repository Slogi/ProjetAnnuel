Pour executer les scripts scrapy à la racine de chaque crawler, c'est à dire, "market_monitor" et "Darty":

scrapy crawl product

Pour que le script Darty fonctionne, il est nécessaire que scrapy utilise python3,
si scrapy utilise python2, il suffit d'utiliser l'environnement virtuel situé dans le dossier "scrapy" appelé "envScrap"

Le script weeklycron.sh est le script executé par notre cronjob, cependant, l'environnement virtuel n'est pas pris en compte :"ne pas l'utiliser"
