// General JS functions ----------------------------------------------------------------------------
/**
 * Create a Promise(request) based on the body provided as the argument
 * @param {string} request_body
 * @returns - Async Promise(request)
 */
async function send_request(request_body) {
  return await fetch("php/requests.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: request_body,
  });
}

/**
 * Main function(just like in java) that determines what code to execute based on the page the user is on.
 */
function main() {
  /* Search functionality(always available) */
  document.getElementById("search_button").addEventListener("click", function () {
    document.location.href = "/search?query=" + document.getElementById("search_value").value;
  });

  if (document.URL.includes("/product")) {
    /* Product page */

    const userHandler = new UserInteractionsHandler();

    // Add to cart functionality
    if (document.getElementById("add_to_cart") != null) {
      document.getElementById("add_to_cart").addEventListener("click", async function (event) {
        event.preventDefault();
        userHandler.add_to_cart();
      });
    }
  } else if (document.URL.includes("/cart")) {
    /* Cart page */

    const userHandler = new UserInteractionsHandler();

    // Remove from cart functionality
    document.getElementById("cart_items").addEventListener("click", function (event) {
      if (event.target.dataset.productid != null) {
        event.preventDefault();
        userHandler.remove_from_cart(event.target);
      }
    });
  } else if (document.URL.includes("/checkout")) {
    /* Checkout page */

    const userHandler = new UserInteractionsHandler();

    // Remove from cart functionality
    document.getElementById("submit_checkout").addEventListener("click", function (event) {
      event.preventDefault();
      userHandler.checkout();
    });
  } else if (document.URL.includes("/signup")) {
    /* Sing Up page */

    const accountLogger = new AccountLogger();

    // Conditions for stings to check upon
    const allSymbols = ["!", "`", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "=", ";", ":", ",", ".", "/", "?", "<", ">", "|", "'", "\\", '"', "[", "]", "{", "}", "_"];
    const numbers = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
    const emailConditions = ["@", "."];

    // DOM elements
    const DOMdata = {
      fname: document.getElementById("fname"),
      lname: document.getElementById("lname"),
      email: document.getElementById("email"),
      uname: document.getElementById("uname"),
      psw: document.getElementById("psw"),
      cpsw: document.getElementById("cpsw"),
      accType_buyer: document.getElementById("accType_buyer"),
      accType_seller: document.getElementById("accType_seller"),
      allSymbols: allSymbols,
      numbers: numbers,
      emailConditions: emailConditions,
    };

    document.getElementById("signup_form").addEventListener("submit", function (event) {
      event.preventDefault();
      accountLogger.signup_handler(DOMdata);
    });
  } else if (document.URL.includes("/signin")) {
    /* Sing In page */

    const formDOM = document.getElementById("signin_form");
    const accountLogger = new AccountLogger();

    // Provide either sign-in or sign-out functionality
    if (formDOM) {
      formDOM.addEventListener("submit", function (event) {
        event.preventDefault();
        accountLogger.singin_handler();
      });
    } else {
      document.getElementById("signout_form").addEventListener("submit", function (event) {
        event.preventDefault();
        accountLogger.signout_handler();
      });
    }
  }
}

// Actual run-time execution --------------------------------------------------------------
main();
