// Fonction pour éditer un produit
function editProduct(productId,userId) {
    // Récupérer les informations du produit via AJAX
    fetch(`/login-registration-with-jwt/api/products/${productId}`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`,
            'Content-Type': 'application/json'
        }
    })
        .then(responses => responses.json())
        .then(response => {
            let data= response.data;
            const modalContent = `
            <div class="modal fade" id="editProductModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier le produit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editProductForm">
                                <input type="hidden" name="id" value="${data.id}">
                                <div class="mb-3">
                                    <label class="form-label">Nom du produit</label>
                                    <input type="text" class="form-control" name="name" value="${data.name}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description du produit</label>
                                    <input type="text" class="form-control" name="description" value="${data.description}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Code barre du produit</label>
                                    <input type="text" class="form-control" name="barcode" value="${data.barcode}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Prix du produit</label>
                                    <input type="text" class="form-control" name="price" value="${data.price}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Unite du produit</label>
                                    <input type="text" class="form-control" name="unit" value="${data.unit}" required>
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" class="form-control" name="user_id" value="${userId}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catégorie</label>
                                    <input type="text" class="form-control" name="category_id" value="${data.category_id}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" class="form-control" name="min_stock" value="${data.min_stock}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Quantité maximale</label>
                                    <input type="number" class="form-control" name="max_stock" value="${data.max_stock}" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="saveProductChanges()">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            // Ajouter la modale au document
            document.body.insertAdjacentHTML('beforeend', modalContent);

            // Initialiser et afficher la modale
            const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
            modal.show();

            // Nettoyer la modale après fermeture
            document.getElementById('editProductModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la récupération des données du produit');
        });
}

// Fonction pour sauvegarder les modifications
function saveProductChanges() {
    const form = document.getElementById('editProductForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());


    fetch(`/login-registration-with-jwt/api/products/${data.id}`, {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.status) {
                bootstrap.Modal.getInstance(document.getElementById('editProductModal')).hide();
                location.reload();
            } else {
                console.error('Erreur:', result.message);
                alert('Erreur lors de la sauvegarde des modifications du produit');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la sauvegarde des modifications');
        });
}


// Fonction pour afficher le formulaire d'ajout de produit
function showAddProductForm(userId) {
    const modalContent = `
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="mb-3">
                            <label class="form-label">Nom du produit</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description du produit</label>
                            <input type="text" class="form-control" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code barre du produit</label>
                            <input type="text" class="form-control" name="barcode" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prix du produit</label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unité du produit</label>
                            <input type="text" class="form-control" name="unit" required>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" class="form-control" name="user_id" value="${userId}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <input type="number" class="form-control" name="category_id" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantité minimale</label>
                            <input type="number" class="form-control" name="min_stock" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantité maximale</label>
                            <input type="number" class="form-control" name="max_stock" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="saveNewProduct()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
    `;

    // Ajouter la modale au document
    document.body.insertAdjacentHTML('beforeend', modalContent);

    // Initialiser et afficher la modale
    const modal = new bootstrap.Modal(document.getElementById('addProductModal'));
    modal.show();

    // Nettoyer la modale après fermeture
    document.getElementById('addProductModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Fonction pour sauvegarder le nouveau produit
function saveNewProduct() {
    const form = document.getElementById('addProductForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Log des données envoyées
    console.log('Données envoyées:', data);

    fetch('/login-registration-with-jwt/api/products', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            // Afficher la réponse brute
            return response.text().then(text => {
                console.log('Réponse brute du serveur:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Réponse serveur invalide: ' + text);
                }
            });
        })
        .then(result => {
            if (result.status === "success") {
                bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
                location.reload();
            } else {
                alert(result.message || 'Erreur lors de l\'ajout du produit');
            }
        })
        .catch(error => {
            console.error('Erreur complète:', error);
            alert('Erreur lors de l\'ajout du produit');
        });
}

// Fonction pour visualiser un produit
function viewProduct(productId) {
    fetch(`/login-registration-with-jwt/api/products/${productId}`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`,
            'Content-Type': 'application/json'
        }
    })
        .then(responses => responses.json())
        .then(response => {
            let data = response.data;
            // Calculer le pourcentage de stock
            const stockPercentage = (data.min_stock / data.max_stock) * 100;
            let statusClass = 'bg-success';
            let statusLabel = 'Normal';

            if (stockPercentage <= 25) {
                statusClass = 'bg-danger';
                statusLabel = 'Critique';
            } else if (stockPercentage <= 50) {
                statusClass = 'bg-warning';
                statusLabel = 'Faible';
            }

            // Créer et afficher la modale de visualisation
            const modalContent = `
            <div class="modal fade" id="viewProductModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Détails du produit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-light p-3 rounded me-3">
                                    <i class="fas fa-box fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1">${data.name}</h4>
                                    <span class="badge bg-light text-dark">#${data.barcode}</span>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label text-muted">Catégorie</label>
                                    <p class="h6">${data.category_id}</p>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted">Statut</label>
                                    <p class="h6">
                                        <span class="badge ${statusClass}">${statusLabel}</span>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted">Niveau de stock</label>
                                    <div class="progress mb-2" style="height: 10px;">
                                        <div class="progress-bar ${statusClass}" 
                                             style="width: ${stockPercentage}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        ${data.min_stock} unités sur ${data.max_stock}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            // Ajouter la modale au document
            document.body.insertAdjacentHTML('beforeend', modalContent);

            // Initialiser et afficher la modale
            const modal = new bootstrap.Modal(document.getElementById('viewProductModal'));
            modal.show();

            // Nettoyer la modale après fermeture
            document.getElementById('viewProductModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la récupération des données du produit');
        });
}