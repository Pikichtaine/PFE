// (DOM)

let spawn=document.getElementById("buton")
let input= document.getElementById("search")
let contenedor=document.getElementById("grid")
let categorias=document.getElementById("category")
let orden=document.getElementById("sort")
let count=document.getElementById("count")

// (/DOM)



// (Refresh)

window.onload = fetchito();

// (/Refresh)



// (Fetch)

function fetchito(){
fetch('https://fakestoreapi.com/products')
.then(reponce=>reponce.json())
.then(data=>{

    console.log("Your Data is completed ", data)

let valor = input.value.toLowerCase();
let selectValue = categorias.value;
let ordenValue= orden.value
    contenedor.innerHTML=""

    // (Spawn All)

    if(valor==="" && selectValue==="all" && ordenValue==="default"){
    data.forEach(aray=>{ 
            spanwCard(aray);
        })
        count.innerText=data.length

    // (/Spawn All)

    }else{

    // (Spawn Filter)

let produitsFiltres = data.filter(p =>
    (p.title.toLowerCase()).includes(valor) && (selectValue === "all" || p.category === selectValue))



        // (Order Filter)

    if (ordenValue === "price-asc") {
    produitsFiltres.sort((a, b) => a.price - b.price);
} else if (ordenValue === "price-desc") {
    produitsFiltres.sort((a, b) => b.price - a.price);
} else if (ordenValue === "title-asc") {
    produitsFiltres.sort((a, b) => a.title.localeCompare(b.title));
}
        // (/Order Filter)



produitsFiltres.forEach(p => {
    spanwCard(p)
})
count.innerText=produitsFiltres.length
}

    // (/Spawn Filter)



});

}

// (/Fetch)



// (Input Function)

input.addEventListener("input", function(){
    fetchito()
})

// (/Input Function)



// (Spawn Product)

  function spanwCard(p){
let tarjeta = document.createElement("article")
tarjeta.classList.add("card");
tarjeta.innerHTML=`
    <div class="imgwrap">
    <img src="${p.image}" alt="fotito">
    </div>
    <div class="title">${p.title}</div>
    <div class="cat">${p.category}</div>
    <div class="price">Prix: ${p.price}€</div>
    `;
  contenedor.appendChild(tarjeta)
}

// (/Spawn Product)



// (Category Select)

categorias.addEventListener("change", () => {
    input.value=""
    fetchito()
});

// (/Category Select)



// (Sort Select)

orden.addEventListener("change", () => {
    input.value=""
    fetchito()
});

// (/Sort Select)