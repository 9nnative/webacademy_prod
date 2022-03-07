$('.ui .form')
  .form({
    inline : true,
    on: 'blur',
    fields: {
      title: {
        identifier  : 'group_prompt_content',
        rules: [
          {
            type   : 'empty',
            prompt : 'Veuillez renseigner votre message'
          },
          {
            type   : 'maxLength[200]',
            prompt : 'Le titre ne doit pas dépasser {ruleValue} caractères'
          }
        ]
      },
      categories: {
        identifier: 'categories',
        rules: [
          {
            type   : 'minCount[1]',
            prompt : 'Veuillez sélectionner au moins une catégorie'
          }
        ]
      },
    },
})