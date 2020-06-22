from config import index

# Registration selectors
clipboard_btn = '/html/body/div[1]/div/div/div[2]/div[1]/form/div[2]/button'
fname = '//*[@id="first_name"]'
lname = '//*[@id="last_name"]'
email = '//*[@id="email"]'
pwd = '//*[@id="password"]'
pwd_confirm = '//*[@id="confirm_password"]'
submit_reg = '//*[@id="btn"]'
activation_link = '//a[contains(@href,"' + index + '")]'
received_mail = '//span[contains(text(),"DARKET")]'

# Log in selectors
login_email = '//*[@id="login_email"]'
login_pwd = '//*[@id="login_password"]'
login_submit = '//*[@id="btn_login"]'

# Catalog selectors
catalog_nav = '//*[@id="navigation"]'

# Checkout selectors
city = '//*[@id="city"]'
state = '//*[@id="state"]'
address = '//*[@id="address_1"]'
post_code = '//*[@id="post_code"]'
checkout_submit = '//*[@id="btn"]'

# Summary selectors
shipping_6 = '//*[@id="shipping_6"]'
go_to_paypal = '/html/body/div[2]/div/div[2]/div[1]/form/div[1]'

# Paypal selectors
pp_login_submit = '//*[@id="btnLogin"]'
pp_pay_submit = '//*[@id="confirmButtonTop"]'
pp_back_to_darket = '//*[@id="merchantReturnBtn"]'
