Pour executer les scripts scrapy � la racine de chaque crawler, c'est � dire, "market_monitor" et "Darty":

scrapy crawl product

Pour que le script Darty fonctionne, il est n�cessaire que scrapy utilise python3,
si scrapy utilise python2, il suffit d'utiliser l'environnement virtuel situ� dans le dossier "scrapy" appel� "envScrap"

Le script weeklycron.sh est le script execut� par notre cronjob, cependant, l'environnement virtuel n'est pas pris en compte :"ne pas l'utiliser"
