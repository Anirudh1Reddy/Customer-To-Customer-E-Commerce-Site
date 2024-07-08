// ! npm run generate_doc to generate JS documentation

/**
 * AccountLogger is a class that is declared and utilized whenever sign-in or sign-out functionality is needed
 */
class AccountLogger {
  /**
   * Sign-up validation. In case data is valid => sends the form to php with a request = "sign_up" to make a new account.
   * @param {Object} data - Set of data. Consists of values given by the user and of the conditions to check on.
   */
  async signup(data) {
    const form = {
      fname: data.fname.value,
      lname: data.lname.value,
      uname: data.uname.value,
      psw: data.psw.value,
      cpsw: data.cpsw.value,
      email: data.email.value,
      accType_seller: data.accType_seller.checked,
      accType_buyer: data.accType_buyer.checked,
      messageReciever: document.getElementById("auth_msgReciever"),
    };

    try {
      const response = await send_request(
        `request=sign_up&fname=${form.fname}&lname=${form.lname}&uname=${form.uname}&psw=${form.psw}&cpsw=${form.cpsw}&email=${form.email}&accType_seller=${form.accType_seller}&accType_buyer=${form.accType_buyer}`
      ).then((data) => {
        if (!data.ok) {
          throw new Error("Server related problem(promise didn't come back)");
        }

        return data.json();
      });

      if (response.status) {
        form.messageReciever.classList.remove("auth_error");
        form.messageReciever.classList.add("auth_success");
        form.messageReciever.innerHTML = `Your account was successfully created`;

        document.getElementById("singup_btn").remove();
        document.getElementById("return_btn").innerHTML = '<a class="link" href="/signin">Return to sign-in page</a>';
      } else {
        throw new Error(response.message);
      }
    } catch (error) {
      form.messageReciever.classList.remove("auth_success");
      form.messageReciever.classList.add("auth_error");
      form.messageReciever.innerHTML = `${error}`;
    }

    form.messageReciever.classList.remove("hide");
  }

  /**
   * Function that is called whenever a sign up button is pressed. Validates and send request to PHP via signup() function.
   * @param {Object} data - Set of data. Consists of values given by the user and of the conditions to check on.
   */
  signup_handler(data) {
    // Checks (True stands for an error)
    const fname_status = data.allSymbols.some((el) => data.fname.value.includes(el)) || data.numbers.some((el) => data.fname.value.includes(el));
    const lname_status = data.allSymbols.some((el) => data.lname.value.includes(el)) || data.numbers.some((el) => data.lname.value.includes(el));
    const email_status = !data.emailConditions.every((el) => data.email.value.includes(el)) || data.email.value.length < 6;
    const username_status = data.allSymbols.some((el) => data.uname.value.includes(el)) || data.uname.value.length < 6;
    const password_status = data.psw.value != data.cpsw.value || data.psw.length < 6;
    const accType_status = !data.accType_buyer.checked && !data.accType_seller.checked;

    // Check if first name or last name fields have symbols
    if (fname_status || lname_status) {
      alert("First and last names can't have any special characters or numbers");
    } else if (username_status) {
      // Passwords must match and their length must be >= 6
      alert("Username can't have any special characters and must be greater than 6 symbols");
    } else if (password_status) {
      // Passwords must match and their length must be >= 6
      alert("Passwords must match and must be greater than 6 symbols");
    } else if (email_status) {
      // Email has to be >= 6 and must have "@" and "."
      alert("Invalid email");
    } else if (accType_status) {
      // Account type must be selected
      alert("Account type is not selected");
    } else {
      this.signup(data);
    }
  }

  /**
   * Function that sends sign_in request to PHP and processes the JSON Promise
   * @param {Object} form - Set of data collected that includes username, password, and object that can be used to display errors
   */
  async singin(form) {
    try {
      const response = await send_request(`request=sign_in&uname=${form.uname}&psw=${form.psw}`).then((data) => {
        if (!data.ok) {
          throw new Error("Server related problem(promise didn't come back)");
        }

        return data.json();
      });

      if (response.status) {
        send_request(`request=init_session&id=${response.id}&user_type=${response.user_type}&first_name=${response.first_name}&last_name=${response.last_name}&email=${response.email}&products_rated=${response.products_rated}`).then((_) => {
          window.location.reload();
        });
      } else {
        throw new Error(response.message);
      }
    } catch (error) {
      form.messageReciever.classList.remove("auth_success");
      form.messageReciever.classList.add("auth_error");
      form.messageReciever.innerHTML = `${error}`;
    }

    form.messageReciever.classList.remove("hide");
  }

  /**
   * Function that is called whenever a sign in button is pressed. Validates and sends request to PHP via signin() function.
   */
  singin_handler() {
    const form = {
      uname: document.getElementById("signin_uname").value,
      psw: document.getElementById("signin_psw").value,
      messageReciever: document.getElementById("auth_msgReciever"),
    };

    this.singin(form);
  }

  /**
   * Function that sends delete_session request to PHP and processes the JSON Promise
   */
  async signout() {
    const messageReciever = document.getElementById("auth_msgReciever");

    try {
      await send_request(`request=delete_session`).then((data) => {
        if (!data.ok) {
          throw new Error("Server related problem(promise didn't come back)");
        }
      });

      // Refer to the home page
      document.location.href = "/";
    } catch (error) {
      messageReciever.classList.remove("auth_success");
      messageReciever.classList.add("auth_error");
      messageReciever.innerHTML = `${error}`;
    }

    messageReciever.classList.remove("hide");
  }

  /**
   * Function that is called whenever a sign out button is pressed. Validates and sends request to PHP via signout() function.
   */
  signout_handler() {
    this.signout();
  }
}

/**
 * UserInteractionsHandler is a class that is declared and utilized whenever any signed-in User functionality is needed
 */
class UserInteractionsHandler {
  /**
   * This function adds selected product infromation to $_SESSION['cart'] of a user via async promise and PHP.
   */
  async add_to_cart() {
    // Required data
    const data = {
      quantity: document.getElementById("product_quantity").value,
      product_id: document.getElementById("add_to_cart").dataset.productid,
      parent_div: document.getElementById("p_add_to_cart"),
    };

    // Send request for a new session
    try {
      if (+data.quantity < 1) {
        throw new Error("Quantity value is invalid");
      }

      const response = await send_request(`request=add_to_cart&id=${data.product_id}&quantity=${data.quantity}`).then((data) => {
        if (!data.ok) {
          throw new Error("Server related problem(promise didn't come back)");
        }

        return data.json();
      });

      if (response.status) {
        data.parent_div.classList.add("auth_success");
        data.parent_div.innerHTML = `${response.message}`;
      } else {
        throw new Error(response.message);
      }
    } catch (error) {
      data.parent_div.classList.add("auth_error");
      data.parent_div.innerHTML = `${error}`;
    }
  }

  /**
   * This function removes selected product infromation from $_SESSION['cart'] of a user via async promise and PHP.
   * @param {Object} target - HTML object, button, which holds id of the product to be removed.
   */
  async remove_from_cart(target) {
    // Required data
    const data = {
      product_id: target.dataset.productid,
      parent_div: target.parentNode,
    };

    // Send request for a new session
    try {
      const response = await send_request(`request=remove_from_cart&id=${data.product_id}`).then((data) => {
        if (!data.ok) {
          throw new Error("Server related problem(promise didn't come back)");
        }

        return data.json();
      });

      if (response.status) {
        data.parent_div.classList.add("auth_success");
        data.parent_div.innerHTML = `${response.message}`;
      } else {
        throw new Error(response.message);
      }
    } catch (error) {
      data.parent_div.classList.add("auth_error");
      data.parent_div.innerHTML = `${error}`;
    }
  }

  /**
   * This function generates and inserts an order to DB based off $_SESSION['cart']; After its execution, $_SESSION['cart'] is set to empty array.
   */
  async checkout() {
    // Required data
    const data = {
      parent_div: document.getElementById("information_checkout"),
    };

    // Send request for a new session
    try {
      // ! Check that fields are not empty
      let card_info = "";
      let address_info = "";
      let shipping_method = "";
      const parent = document.getElementById("information_checkout");

      // Generate card_info
      const name_card = document.getElementById("name_card").value.trim();
      const number_card = document.getElementById("number_card").value.trim();
      const expiration_card = document.getElementById("expiration_card").value.trim();
      const cvc_card = document.getElementById("cvc_card").value.trim();
      if (name_card.length == 0 || number_card.length == 0 || expiration_card.length == 0 || cvc_card.length == 0) {
        parent.innerHTML += "<p class='auth_error' style='text-align: center; margin-top: 2rem;' >Not enough information provided to one or more fields in 'Payment Details'.</p>";
        return;
      } else {
        card_info += `${name_card}|${number_card}|${expiration_card}|${cvc_card}`;
      }

      // Generate address_info
      const address_address = document.getElementById("address_address").value.trim();
      const city_address = document.getElementById("city_address").value.trim();
      const state_address = document.getElementById("state_address").value.trim();
      const zip_address = document.getElementById("zip_address").value.trim();
      if (address_address.length == 0 || city_address.length == 0 || state_address.length == 0 || zip_address.length == 0) {
        parent.innerHTML += "<p class='auth_error' style='text-align: center; margin-top: 2rem;' >Not enough information provided to one or more fields in 'Confirm Address'.</p>";
        return;
      } else {
        address_info += `${address_address}|${city_address}|${state_address}|${zip_address}`;
      }

      // Generate shipping_method
      shipping_method = document.getElementById("shipping_methods").value.trim();
      if (shipping_method.length == 0) {
        parent.innerHTML += "<p class='auth_error' style='text-align: center; margin-top: 2rem;' >No shipping method chosen.</p>";
        return;
      }

      console.log(shipping_method);

      const response = await send_request(`request=checkout&card_info=${card_info}&address_info=${address_info}&shipping_method=${shipping_method}`).then((data) => {
        if (!data.ok) {
          throw new Error("Server related problem(promise didn't come back)");
        }

        return data.json();
      });

      if (response.status) {
        data.parent_div.classList.add("auth_success");
        data.parent_div.innerHTML = `${response.message}`;
      } else {
        throw new Error(response.message);
      }
    } catch (error) {
      data.parent_div.classList.add("auth_error");
      data.parent_div.innerHTML = `${error}`;
    }
  }
}
