from selenium.webdriver import ChromeOptions, Chrome
from selenium.webdriver.common.keys import Keys
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.support.ui import WebDriverWait

from random import randint
from datetime import date

from config import *
from selectors import *

import unittest
import clipboard


class Darket(unittest.TestCase):
    def setUp(self):
        opts = ChromeOptions()
        opts.add_experimental_option('detach', True)
        self.browser = Chrome(options=opts)
        self.test_id = str(randint(1, 9999))
        self.date_today = str(date.today())
        self.browser.implicitly_wait(100)

    def click_if_ele_exists_by_xpath(self, xpath):
        try:
            received_mail = self.browser.find_element_by_xpath(xpath)
            received_mail.click()
        except NoSuchElementException:
            return False
        return True

    def sign_in(self, email, pwd, switch=None):
        b = self.browser
        if switch is not None:
            b.get(panel_url)
        else:
            b.get(login_url)
        b.find_element_by_xpath(login_email).send_keys(email)
        b.find_element_by_xpath(login_pwd).send_keys(pwd)
        b.find_element_by_xpath(login_submit).click()

    def scroll_page(self):
        self.browser.execute_script("window.scrollTo(0, 1080)")

    def open_new_tab(self):
        self.browser.execute_script("window.open();")

    def test_reg(self):
        b = self.browser
        password = self.test_id + self.date_today.replace('-', '')

        # get temp mail
        b.get(email_url)
        email_tab = b.current_window_handle
        b.maximize_window()
        b.find_element_by_xpath(clipboard_btn).click()
        temp_email = clipboard.paste()

        # open new tab with login page
        b.execute_script("window.open();")
        b.switch_to_window(b.window_handles[1])
        b.get(login_url)
        login_tab = b.current_window_handle

        # fill in required fields
        b.find_element_by_xpath(fname).send_keys('Имя' + self.test_id)
        b.find_element_by_xpath(lname).send_keys('Фамилия' + self.test_id)
        b.find_element_by_xpath(email).send_keys(temp_email)
        b.find_element_by_xpath(pwd).send_keys(password)
        b.find_element_by_xpath(pwd_confirm).send_keys(password)
        b.find_element_by_xpath(submit_reg).click()
        b.close()
        print('Registration successful')

        # back to temp mail and look for activation mail
        b.switch_to_window(email_tab)

        # refresh the page until mail found then click
        while not self.click_if_ele_exists_by_xpath(received_mail):
            print('Activation mail is not received yet, refreshing the page..')
            b.refresh()
        print('Activation mail found, opening..')
        # scroll the page until activation link is not visible
        while not self.click_if_ele_exists_by_xpath(activation_link):
            print('Activation link not found, scrolling the page..')
            self.scroll_page()
        print('Account has been activated')

        # open new tab with login page in it
        self.open_new_tab()
        b.switch_to_window(b.window_handles[1])
        self.sign_in(temp_email, password)

    def test_payment(self):
        b = self.browser
        b.maximize_window()
        self.sign_in(test_email, test_pwd)
        # add items to cart
        b.get(index)
        btns = b.find_elements_by_class_name('add_to_cart')
        for i in range(0, len(btns)):
            if btns[i].is_displayed():
                btns[i].click()
            else:
                self.scroll_page()
                btns[i].click()
        # proceed to checkout and fill in all required fields
        b.get(checkout_url)
        '''
        b.find_element_by_xpath(city).send_keys('Тест город')
        b.find_element_by_xpath(state).send_keys('Тест область')
        b.find_element_by_xpath(address).send_keys('Тест адрес д.32, кв.15')
        b.find_element_by_xpath(post_code).send_keys('630102')
        '''
        # submit checkout
        b.find_element_by_xpath(checkout_submit).click()
        # choose shipping
        b.find_element_by_xpath(shipping_6).click()
        # go to paypal
        b.find_element_by_xpath(go_to_paypal).click()
        # enter to the sandbox acc for pay
        paypal_login = b.find_element_by_xpath(email)
        paypal_login.clear()
        paypal_login.send_keys(pp_login)
        b.find_element_by_xpath(pwd).send_keys(pp_pwd)
        b.find_element_by_xpath(pp_login_submit).click()
        b.find_element_by_xpath(pp_pay_submit).click()
        self.open_new_tab()
        b.switch_to_window(b.window_handles[1])
        b.get(orders_url)
        # b.save_screenshot('screenshots/' + self.test_id + '_paypal_done_' +
        #                 self.date_today + '.png')

        # inside paypal log in, click PAY -> back to darket

        # inside darket open orders

    def test_adding_items(self):
        b = self.browser
        b.get(panel_url)
        b.maximize_window()
        self.sign_in(panel_login, pp_pwd, 1)


if __name__ == "__main__":
    unittest.main()
