# -*- coding: utf-8 -*-
from scrapy.linkextractors import LinkExtractor
from scrapy.spiders import CrawlSpider, Rule
from scrapy.loader import ItemLoader
from scrapy.selector import Selector
from ..items import WebMonitorItem, WebMonitorItemLoader
import datetime

class ProductSpider(CrawlSpider):

    handle_httpstatus_list = [301, 302, 404]

    name = 'product'

    start_urls = ["https://www.boulanger.com"]

    allowed_domains = ["boulanger.com"]

    rules = [
        Rule(
            LinkExtractor(
                allow='/c/(.+)',
                restrict_css='.o-header_arch_list'
            )
        ),
        Rule(
            LinkExtractor(
                allow = '/c/(.+)',
                restrict_xpaths = '//*[@id="shp"]/div/div[@class="cat"]/a'
            )
        ),
        Rule(
            LinkExtractor(
                allow='numPage=(\d+)',
                restrict_xpaths='//*[@class="blocListe"]/div[1]/span/span[@class="navPage navPage-right"]/a'
            )
        ),
        Rule(
            LinkExtractor(
                allow='/ref/(.+)',
                restrict_xpaths='//*[@class="productListe"]/div/div[@class="designations"]/div[@class="carac"]/a'),
            callback='parse_product')
    ]

    def parse_product(self, response):
        for sel in response.xpath('//body'):
            l = WebMonitorItemLoader(item=WebMonitorItem(), selector=sel)
            l.add_value('url', response.request.url)
            l.add_value('date', datetime.datetime.today().strftime('%Y-%m-%d'))
            l.add_xpath('ean', '//span[@itemprop="gtin13"]/text()')
            l.add_xpath('product_name', '//h1[@itemprop="name"]/text()')
            l.add_xpath('brand', '//h1[@itemprop="name"]/a/span[@itemprop="brand"]/span[@itemprop="name"]/text()')
            l.add_xpath('price_exponent', '//p[@class="fix-price"]/span[@class="exponent"]/text()')
            l.add_xpath('price_fraction', '//p[@class="fix-price"]/sup/span[@class="fraction"]/text()')
            l.add_xpath('img', '//div[@id="H5V_viewer"]/div[@id="H5V_ZoomView"]/img[@itemprop="image"]/@src')
            yield l.load_item()



