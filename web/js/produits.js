jQuery(document).ready(function() {
    var $collectionHolder = $('div.collectionProduits');
    var $addProduitLink = $('<a href="#" class="btn btn-hyperbrico" title="Ajouter un produit"><i class="icon-plus icon-white"></i> Ajouter un produit</a>');
    var $collectionHolder2 = $('table.collectionProduits2');
    var $index = $('div.collectionProduits table tr').length;

    $collectionHolder2.after($addProduitLink);
    $addProduitLink.on('click', function(e) {
        e.preventDefault();
        addProduitForm($collectionHolder, $collectionHolder2);
    });
    if ($index == 1) {
        addProduitForm($collectionHolder, $collectionHolder2);
    } else {
        $collectionHolder2.children('tbody').children('tr').each(function() {
          addProduitFormDeleteLink($(this));
        });
    }

    function addProduitForm($collectionHolder, $newLinkLi) {
        var $prototype = $collectionHolder.attr('data-prototype');
        var $newForm = $($prototype.replace(/__name__/g, $index));
        addProduitFormDeleteLink($newForm);
        $newLinkLi.append($newForm);
        $index++;
    }

    function addProduitFormDeleteLink($produitFormLi) {
        var $removeFormA = $('<a class="remove btn btn-danger" title="Supprimer le produit" href="#"><i class="icon-remove icon-white"></i></a>');
        var $removeFormAtd = $('<td></td>').append($removeFormA);
        $produitFormLi.append($removeFormAtd);

        $removeFormA.on('click', function(e) {
            e.preventDefault();
            $produitFormLi.remove();
        });
    }
});