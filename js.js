// === NAVIGATION SIDEBAR ===
const perfilBtn        = document.getElementById("perfil");
const articulosBtn     = document.getElementById("articulos");   // null pour clients
const perfilSection    = document.getElementById("perfil-section");
const articulosSection = document.getElementById("articulos-section");

perfilBtn.addEventListener("click", function () {
    perfilBtn.classList.add("active");
    if (articulosBtn) articulosBtn.classList.remove("active");
    perfilSection.style.display = "block";
    articulosSection.style.display = "none";
});

if (articulosBtn) {
    articulosBtn.addEventListener("click", function () {
        articulosBtn.classList.add("active");
        perfilBtn.classList.remove("active");
        articulosSection.style.display = "block";
        perfilSection.style.display = "none";
    });
}

// === EDIT USERNAME ===
const editBtn       = document.getElementById("modificar");
const username      = document.getElementById("usuario");
const usernameInput = document.getElementById("usuarioInput");
const saveBtn       = document.getElementById("guardar");

editBtn.addEventListener("click", (e) => {
    e.preventDefault();
    username.classList.toggle("hidden");
    usernameInput.classList.toggle("hidden");
    editBtn.classList.toggle("hidden");
    saveBtn.classList.toggle("hidden");
});

// === DEALER PANEL ===
const dealerBtn     = document.getElementById("dealerBtn");     // null pour dealers
const dealerOverlay = document.getElementById("dealerOverlay");
const dealerStep1   = document.getElementById("dealerStep1");
const dealerStep2   = document.getElementById("dealerStep2");
const dot1          = document.getElementById("dot1");
const dot2          = document.getElementById("dot2");

function setDots(step) {
    dot1.classList.toggle("active", step === 1);
    dot2.classList.toggle("active", step === 2);
}

function closePanel() {
    dealerOverlay.classList.add("hidden");
    // Reset vers step 1 après fermeture
    setTimeout(() => {
        dealerStep2.classList.add("hidden");
        dealerStep1.classList.remove("hidden");
        setDots(1);
    }, 200);
}

function goToStep(from, to) {
    from.classList.add("hidden");
    to.classList.remove("hidden");
    to.style.animation = "dealerStepIn 0.3s ease";
    setTimeout(() => to.style.animation = "", 300);
}

if (dealerBtn) {
    dealerBtn.addEventListener("click", (e) => {
        e.preventDefault();
        dealerOverlay.classList.remove("hidden");
    });
}

document.getElementById("closeDealer").addEventListener("click", closePanel);
document.getElementById("closeDealer2").addEventListener("click", closePanel);
document.getElementById("dealerNo").addEventListener("click", closePanel);

dealerOverlay.addEventListener("click", (e) => {
    if (e.target === dealerOverlay) closePanel();
});

document.getElementById("dealerStart").addEventListener("click", () => {
    goToStep(dealerStep1, dealerStep2);
    setDots(2);
});

document.getElementById("dealerBack").addEventListener("click", () => {
    goToStep(dealerStep2, dealerStep1);
    setDots(1);
});






const perfilBtn        = document.getElementById("perfil");
const articulosBtn     = document.getElementById("articulos");
const perfilSection    = document.getElementById("perfil-section");
const articulosSection = document.getElementById("articulos-section");

perfilBtn.addEventListener("click", function () {
    perfilBtn.classList.add("active");
    if (articulosBtn) articulosBtn.classList.remove("active");
    perfilSection.style.display = "block";
    articulosSection.style.display = "none";
});

if (articulosBtn) {
    articulosBtn.addEventListener("click", function () {
        articulosBtn.classList.add("active");
        perfilBtn.classList.remove("active");
        articulosSection.style.display = "block";
        perfilSection.style.display = "none";
    });
}


const editBtn = document.getElementById("modificar");
const username = document.getElementById("usuario");
const usernameInput = document.getElementById("usuarioInput");
const saveBtn = document.getElementById("guardar");



editBtn.addEventListener("click", (e) => {
    e.preventDefault();
    username.classList.toggle("hidden");
    usernameInput.classList.toggle("hidden");
    editBtn.classList.toggle("hidden");
    saveBtn.classList.toggle("hidden");

});

const dealerBtn     = document.getElementById("dealerBtn");     // null pour dealers
const dealerOverlay = document.getElementById("dealerOverlay");
const dealerStep1   = document.getElementById("dealerStep1");
const dealerStep2   = document.getElementById("dealerStep2");
const dot1          = document.getElementById("dot1");
const dot2          = document.getElementById("dot2");

function setDots(step) {
    dot1.classList.toggle("active", step === 1);
    dot2.classList.toggle("active", step === 2);
}

function closePanel() {
    dealerOverlay.classList.add("hidden");
    // Reset vers step 1 après fermeture
    setTimeout(() => {
        dealerStep2.classList.add("hidden");
        dealerStep1.classList.remove("hidden");
        setDots(1);
    }, 200);
}

function goToStep(from, to) {
    from.classList.add("hidden");
    to.classList.remove("hidden");
    to.style.animation = "dealerStepIn 0.3s ease";
    setTimeout(() => to.style.animation = "", 300);
}

if (dealerBtn) {
    dealerBtn.addEventListener("click", (e) => {
        e.preventDefault();
        dealerOverlay.classList.remove("hidden");
    });
}

document.getElementById("closeDealer").addEventListener("click", closePanel);
document.getElementById("closeDealer2").addEventListener("click", closePanel);
document.getElementById("dealerNo").addEventListener("click", closePanel);

dealerOverlay.addEventListener("click", (e) => {
    if (e.target === dealerOverlay) closePanel();
});

document.getElementById("dealerStart").addEventListener("click", () => {
    goToStep(dealerStep1, dealerStep2);
    setDots(2);
});

document.getElementById("dealerBack").addEventListener("click", () => {
    goToStep(dealerStep2, dealerStep1);
    setDots(1);
});
