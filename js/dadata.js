$(document).ready(function() {
    // Проверяем, загружена ли библиотека suggestions
    if (typeof $.fn.suggestions === 'undefined') {
        console.error('DaData Suggestions plugin is not loaded!');
        return;
    }

    const DADATA_API_KEY = "4abf47bcb873b0c8a1051c12c76b1dc98fa3359f";

    function getFieldType(input) {
        const name = input.name || input.id || '';
        const lowerName = name.toLowerCase();

        if (lowerName.includes('address') || lowerName.includes('адрес')) {
            return 'ADDRESS';
        }
        if (lowerName.includes('name') || lowerName.includes('фио') || lowerName.includes('fio')) {
            return 'NAME';
        }
        if (lowerName.includes('email') || lowerName.includes('почта') || lowerName.includes('e-mail')) {
            return 'EMAIL';
        }
        if (lowerName.includes('phone') || lowerName.includes('телефон') || lowerName.includes('tel')) {
            return 'PHONE';
        }
        if (lowerName.includes('inn') || lowerName.includes('инн')) {
            return 'PARTY';
        }
        return null;
    }

    $('input').each(function() {
        const fieldType = getFieldType(this);
        if (!fieldType) return;

        const options = {
            token: DADATA_API_KEY,
            type: fieldType,
            count: 5,
            onSelect: function(suggestion) {
                $(this).val(suggestion.value);
            }
        };

        if (fieldType === 'PHONE') {
            options.type = 'MOBILE';
            options.onSelect = function(suggestion) {
                $(this).val(suggestion.data.phone.replace(/^\+7/, ''));
            };
        }

        $(this).suggestions(options);
    });
});