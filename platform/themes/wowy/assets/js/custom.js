 const input = document.querySelector("#phone");
  window.intlTelInput(input, {
    loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.4/build/js/utils.js"),
      initialCountry: "auto",
     
  initialCountry: "auto",
  geoIpLookup: (success, failure) => {
    fetch("https://ipapi.co/json")
      .then((res) => res.json())
      .then((data) => success(data.country_code))
      .catch(() => failure());
  }
  });
    