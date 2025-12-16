const searchInput = document.getElementById("searchInput");
const resultsDiv = document.getElementById("results");

let debounceTimer;

document.addEventListener("click", (event) => {
    const clickedInsideSearch = searchInput.contains(event.target);
    const clickedInsideResults = resultsDiv.contains(event.target);

    if (!clickedInsideSearch && !clickedInsideResults) {
        resultsDiv.style.display = "none";
    }
});


searchInput.addEventListener("input", () => {
    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {
        const query = searchInput.value.trim();
        searchProducts(query);
    }, 300); // opóźnienie 300ms
});

async function searchProducts(query) {
    if (query.length === 0) {
        resultsDiv.innerHTML = "";
        return;
    }

    try {
        const response = await fetch(`../../classes/actions/SearchProductsAction.php?input=` + query);
        const products = await response.json();

        renderResults(products);
    } catch (error) {
        console.error("Błąd podczas wyszukiwania:", error);
    }
}

function renderResults(products) {
    if (!products || products.length === 0) {
        resultsDiv.innerHTML = "<div class='no-results'>Brak wyników</div>";
        resultsDiv.style.display = "block";
        return;
    }

    resultsDiv.innerHTML = products.map(p => `
        <a class="result-item" href="../product/product.php?id=${p.id}">
            <img src="../../../src/assets/images/${p.imageName}" alt="${p.name}">
            <div>
                <strong>${p.name}</strong>
                <span>${p.price} zł</span>
            </div>
        </a>
    `).join("");

    resultsDiv.style.display = "block";
}

