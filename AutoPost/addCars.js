const brands = ["Abarth","Acura","Alfa Romeo","Alpine","Aston Martin","Audi","Bentley","BMW","Bugatti","Buick","BYD","Cadillac","Chevrolet","Chrysler","Citroën","Dacia","Daewoo","Daihatsu","Dodge","DS","Ferrari","Fiat","Fisker","Ford","Genesis","GMC","Honda","Hummer","Hyundai","Infiniti","Isuzu","Jaguar","Jeep","Kia","Koenigsegg","Lada","Lamborghini","Lancia","Land Rover","Lexus","Lincoln","Lotus","Lucid","Maserati","Maybach","Mazda","McLaren","Mercedes-AMG","Mercedes-Benz","MG","Mini","Mitsubishi","NIO","Nissan","Opel","Pagani","Peugeot","Polestar","Pontiac","Porsche","RAM","Renault","Rimac","Rivian","Rolls-Royce","Saab","SEAT","Škoda","Smart","SsangYong","Subaru","Suzuki","Tata","Tesla","Toyota","TVR","Volkswagen","Volvo","XPeng","Zeekr"];
let suggestions = document.getElementById("brands");
let modelos = document.getElementById("modeles");
let input = document.getElementById("marque");
let modelInput = document.getElementById("modele");

let vitesseInput = document.getElementById("vitesse_max");
let rpmMin = document.getElementById("rpm_min");
let rpmMax = document.getElementById("rpm_max");
let classe=document.getElementById("class");
let portes=document.getElementById("portes");
let transmission=document.getElementById("transmission");
let carburant=document.getElementById("carburant");
let acceleration=document.getElementById("acceleration");
let versiones = document.getElementById("versions");
let versionInput = document.getElementById("version");
let marca=null;
let modelao=null;

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
    let marca=modelo;
fetch("./models.json")
.then(reponce=>reponce.json())
.then(data=>{

    console.log("Your Data is completed ", data)
    const models = data[modelo] || [];

if (Array.isArray(models)) {
          showModels(models, modelo);
    
} else {
    let modelsArray = Object.keys(models);
    showModels(modelsArray, modelo);
}
    });
}

function showModels(models, marca) {
  modelos.innerHTML = "";

  models.forEach(model => {
    const li = document.createElement("li");
    li.textContent = model;

    li.addEventListener("click", () => {
      modelInput.value = model;
      modelos.innerHTML = "";
      otroFetch(marca, model);
      Specs(model);
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
                otroFetch(marca, model);
                Specs(model);
            });
            modelos.appendChild(li);
        });
        selectedModel = 0;
        modeloSeleccionado();
    }else if(value === ""){
        showModels(models, marca);
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
function Specs(modelo){
fetch(`get_specs.php?modele=${encodeURIComponent(modelo)}`)
  .then(res => res.json())
  .then(data => {
    vitesseInput.value = data["Vitesse maximale"];
    rpmMin.value = data["RPM-min"];
    rpmMax.value = data["RPM_max"];
    classe.value = data["Class"];
    portes.value = data["Portes"];
    transmission.value = data["Transmission"];
    carburant.value = data["Type de carburant"];
    acceleration.value = data["Acceleration 0-100"];
  });
}

function otroFetch(marca,version){
fetch("./models.json")
.then(reponce=>reponce.json())
.then(data=>{

    console.log("Your Data is completed ", data)
    const versiones = data[marca][version] || [];
      showVersions(versiones);
    });
}

function showVersions(versions) {
  versiones.innerHTML = "";

  versions.forEach(version => {
    const li = document.createElement("li");
    li.textContent = version;

    li.addEventListener("click", () => {
      versionInput.value = version;
      versiones.innerHTML = "";
    });

    versiones.appendChild(li);
  });

versionInput.addEventListener("input", function() {
    let value = this.value.toLowerCase();
    versiones.innerHTML = "";
    if (value) {
        let filteredVersions = versions.filter(version => version.toLowerCase().includes(value));
        filteredVersions.forEach(version => {
            let li = document.createElement("li");
            li.textContent = version;
            li.addEventListener("click", function() {
                versionInput.value = this.textContent;
                versiones.innerHTML = "";
            });
            versiones.appendChild(li);
        });

    }else if(value === ""){
        showVersions(versions);
    }
});

}