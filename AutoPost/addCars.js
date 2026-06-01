const brands = ["Abarth","Acura","Alfa Romeo","Alpine","Aston Martin","Audi","Bentley","BMW","Bugatti","Buick","BYD","Cadillac","Chevrolet","Chrysler","Citroën","Dacia","Daewoo","Daihatsu","Dodge","DS","Ferrari","Fiat","Fisker","Ford","Genesis","GMC","Honda","Hummer","Hyundai","Infiniti","Isuzu","Jaguar","Jeep","Kia","Koenigsegg","Lada","Lamborghini","Lancia","Land Rover","Lexus","Lincoln","Lotus","Lucid","Maserati","Maybach","Mazda","McLaren","Mercedes-AMG","Mercedes-Benz","MG","Mini","Mitsubishi","NIO","Nissan","Opel","Pagani","Peugeot","Polestar","Pontiac","Porsche","RAM","Renault","Rimac","Rivian","Rolls-Royce","Saab","SEAT","Škoda","Smart","SsangYong","Subaru","Suzuki","Tata","Tesla","Toyota","TVR","Volkswagen","Volvo","XPeng","Zeekr"];
let suggestions = document.getElementById("brands");
let modelos = document.getElementById("modeles");
let input = document.getElementById("marque");
let modelInput = document.getElementById("modele");
let selectedIndex = -1;
let selectedModel = -1;

            brands.forEach(brand => {
            let li = document.createElement("li");
            li.textContent = brand;
            li.addEventListener("click", function() {
                let modelo=this.textContent;
                input.value = this.textContent;
                suggestions.innerHTML = "";
                fetchito(modelo)
            });
            suggestions.appendChild(li);
        });
        selectedIndex = 0;
        listaSeleccionada();
input.addEventListener("input", function() {
    let value = this.value.toLowerCase();
    suggestions.innerHTML = "";
    if (value) {
        let filteredBrands = brands.filter(brand => brand.toLowerCase().startsWith(value));
        filteredBrands.forEach(brand => {
            let li = document.createElement("li");
            li.textContent = brand;
            li.addEventListener("click", function() {
                let modelo=this.textContent;
                input.value = this.textContent;
                suggestions.innerHTML = "";
                fetchito(modelo)
            });
            suggestions.appendChild(li);
        });
            selectedIndex = 0;
            listaSeleccionada();
    }else if(value === ""){
            brands.forEach(brand => {
            let li = document.createElement("li");
            li.textContent = brand;
            li.addEventListener("click", function() {
                let modelo=this.textContent;
                input.value = this.textContent;
                suggestions.innerHTML = "";
                fetchito(modelo);
            });
            suggestions.appendChild(li);
        });
        selectedIndex = 0;
        listaSeleccionada();
    }
});

input.addEventListener("keydown", function(e) {

    const items = suggestions.querySelectorAll("li");

    if (!items.length) return;

    if (e.key === "ArrowDown") {

        e.preventDefault();

        selectedIndex++;

        if (selectedIndex >= items.length) {
            selectedIndex = 0;
        }

        listaSeleccionada();
    }

    else if (e.key === "ArrowUp") {

        e.preventDefault();

        selectedIndex--;

        if (selectedIndex < 0) {
            selectedIndex = items.length - 1;
        }

        listaSeleccionada();
    }

    else if (e.key === "Enter") {

        e.preventDefault();

        if (selectedIndex >= 0) {
            items[selectedIndex].click();
        }
    }

});

function listaSeleccionada() {
    const items = suggestions.querySelectorAll("li");

    items.forEach(item => {
        item.classList.remove("active");
    });

    if (selectedIndex >= 0 && items[selectedIndex]) {
        items[selectedIndex].classList.add("active");

        items[selectedIndex].scrollIntoView({
            behavior: "smooth",
            block: "nearest"
        });
    }
}

function fetchito(modelo){
fetch("./models.json")
.then(reponce=>reponce.json())
.then(data=>{

    console.log("Your Data is completed ", data)
    const models = data[modelo] || [];
      showModels(models);
    });
}
function showModels(models) {
  modelos.innerHTML = "";

  models.forEach(model => {
    const li = document.createElement("li");
    li.textContent = model;

    li.addEventListener("click", () => {
      modelInput.value = model;
      modelos.innerHTML = "";
    });

    modelos.appendChild(li);
  });

        selectedModel = 0;
        modeloSeleccionado();

modelInput.addEventListener("input", function() {
    let value = this.value.toLowerCase();
    modelos.innerHTML = "";
    if (value) {
        let filteredModels = models.filter(model => model.toLowerCase().includes(value));
        filteredModels.forEach(model => {
            let li = document.createElement("li");
            li.textContent = model;
            li.addEventListener("click", function() {
                modelInput.value = this.textContent;
                modelos.innerHTML = "";
            });
            modelos.appendChild(li);
        });
        selectedModel = 0;
        modeloSeleccionado();
    }else if(value === ""){
        showModels(models);
    }
});

}

function modeloSeleccionado() {
    const items = modelos.querySelectorAll("li");

    items.forEach(item => {
        item.classList.remove("active");
    });

    if (selectedModel >= 0 && items[selectedModel]) {
        items[selectedModel].classList.add("active");

        items[selectedModel].scrollIntoView({
            behavior: "smooth",
            block: "nearest"
        });
    }
}

modelInput.addEventListener("keydown", function(e) {

    const items = modelos.querySelectorAll("li");

    if (!items.length) return;

    if (e.key === "ArrowDown") {

        e.preventDefault();

        selectedModel++;

        if (selectedModel >= items.length) {
            selectedModel = 0;
        }

        modeloSeleccionado();
    }

    else if (e.key === "ArrowUp") {

        e.preventDefault();

        selectedModel--;

        if (selectedModel < 0) {
            selectedModel = items.length - 1;
        }

        modeloSeleccionado();
    }

    else if (e.key === "Enter") {

        e.preventDefault();

        if (selectedModel >= 0) {
            items[selectedModel].click();
        }
    }

});