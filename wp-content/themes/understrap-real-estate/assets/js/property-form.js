(function($, propertyForm) {
    $(document).ready(function() {
        let form = $('#property-form form');
        let typeWrapper = $('#property-type');
        let cityWrapper = $('#property-city');
        let responseWrapper = $('#property-form-response');

        createSelect('property-type', propertyForm.types, typeWrapper, 'Выберите тип недвижимости');
        createSelect('property-city', propertyForm.cities, cityWrapper, 'Выберите город');

        form.on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: propertyForm.url,
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    responseWrapper.append($('<h4>').text(response.title))

                    if (response.status) {
                        form.trigger('reset');
                    }
                }
            });
  
            return false;
        });
    });

    function createSelect(name, values, wrapper, prompt) {
        let select = $('<select>').addClass('wpcf7-form-control wpcf7-select').attr('name', name).appendTo(wrapper);

        select.append($('<option>').attr('value', '').text(prompt));
        for (let val in values) {
            select.append($('<option>').attr('value', values[val].id).text(values[val].name));
        }
    }
})(jQuery, propertyForm);

