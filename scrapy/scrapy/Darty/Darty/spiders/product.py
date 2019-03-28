# -*- coding: utf-8 -*-
from scrapy.linkextractors import LinkExtractor
from scrapy.spiders import CrawlSpider, Rule
from scrapy.loader import ItemLoader
from scrapy.selector import Selector
from ..items import DartyItem, DartyItemLoader
import datetime

class ProductSpider(CrawlSpider):

    handle_httpstatus_list = [301, 302, 404]

    name = 'product'

    start_urls = ["https://www.darty.com/nav/achat/gros_electromenager/lave-linge/index.html#dartyclic=X_gros-elec_lave-ling"]

    allowed_domains = ["darty.com"]

    rules = [
        Rule(
            LinkExtractor(
                allow='/nav/',
                restrict_css='.level-2')
            ),
        Rule(
            LinkExtractor(
                allow='/nav/',
                restrict_css='.universe_additional_doc'
            )

        ),
        Rule(
            LinkExtractor(
                allow='/nav/',
                restrict_xpaths="//a[contains(. ,'Page suivante')]"
            )
        ),
        Rule(
            LinkExtractor(
                allow='/nav/achat/',
                deny='/index.html',
                restrict_xpaths="//*[@id='product-list-cont-parent']"
            ),
            callback='parse_product'
        )
    ]

    def parse_product(self, response):
        for sel in response.xpath('//body'):
            l = DartyItemLoader(item=DartyItem(), selector=sel)
            l.add_value('url', response.request.url)
            l.add_value('date', datetime.datetime.today().strftime('%Y-%m-%d'))
            l.add_xpath('ean', '//*[@id="main"]/div[@class="page_product store-in-history-trigger"]/meta[@itemprop="gtin13"]/@content')
            l.add_xpath('product_name', '//div[@class="product_head"]/h1/span/text()')
            l.add_xpath('brand', '//div[@class="product_head"]/h1/span/a/text()')
            l.add_xpath('price_exponent','//div[@class="product_price font-2-b"]/span/text()')
            l.add_xpath('price_fraction','//div[@class="product_price font-2-b"]/span/span/text()')
            l.add_xpath('img', '//div[@class="darty_product_picture_main_pic_container "]/div[1]/div[1]/img[@itemprop="image"]/@src')
            yield l.load_item()



