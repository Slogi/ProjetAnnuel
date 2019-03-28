# -*- coding: utf-8 -*-
import scrapy
from scrapy.loader import ItemLoader
from scrapy.loader.processors import TakeFirst, Compose, MapCompose, Join

clean_text = Compose(MapCompose(lambda v: v.strip()), Join())

class WebMonitorItem(scrapy.Item):
    brand = scrapy.Field()
    product_name = scrapy.Field()
    ean = scrapy.Field()
    price_exponent = scrapy.Field()
    price_fraction = scrapy.Field()
    img = scrapy.Field()
    url = scrapy.Field()
    date = scrapy.Field()

class WebMonitorItemLoader(ItemLoader):
    brand_out = TakeFirst()
    product_name_in = clean_text
    product_name_out = TakeFirst()
    ean_out = TakeFirst()
    price_exponent_out = TakeFirst()
    price_fraction_out = TakeFirst()
    img_out = TakeFirst()
    url_out = TakeFirst()
    date_out = TakeFirst()



