document.querySelectorAll(".carousel").forEach((carousel) => {
  const items = carousel.querySelectorAll(".carousel__item");
  const buttonsContainer = document.createElement("div");
  buttonsContainer.classList.add("carousel__nav");

  const buttonsHtml = Array.from(items, (_, i) => {
      return `<span class="carousel__button" data-index="${i}"></span>`;
  });

  buttonsContainer.innerHTML = buttonsHtml.join("");
  document.body.appendChild(buttonsContainer);

  const buttons = document.querySelectorAll(".carousel__button");
  let currentIndex = 0;

  const showSlide = (index) => {
      // un-select all the items
      items.forEach((item) =>
          item.classList.remove("carousel__item--selected")
      );
      buttons.forEach((button) =>
          button.classList.remove("carousel__button--selected")
      );

      items[index].classList.add("carousel__item--selected");
      buttons[index].classList.add("carousel__button--selected");
  };

  buttons.forEach((button) => {
      button.addEventListener("click", () => {
          currentIndex = parseInt(button.getAttribute("data-index"));
          showSlide(currentIndex);
      });
  });

  const autoPlay = () => {
      currentIndex = (currentIndex + 1) % items.length;
      showSlide(currentIndex);
  };

  // Select the first item on page load
  showSlide(currentIndex);

  // Auto-play every 5 seconds (adjust the interval as needed)
  setInterval(autoPlay, 5000);
});
