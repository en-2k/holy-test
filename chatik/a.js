window.data = 
{
  struct: [
    {
      name: "id",
      outName: "ID",
      type: "number"
    },
    {
      name: "ok",
      outName: "Підтвер<br/>дження",
      type: "boolean"
    },
    {
      name: "name",
      outName: "Найменування",
      type: "string"
    },
    {
      name: "koef",
      outName: "Коефіцієнт",
      type: "number"
    },
    {
      name: "created",
      outName: "Створено",
      type: "date"
    }
  ],
  data: [
    {
      id: 1,
      ok: false,
      name: "Дещо у рядку",
      koef: 3.1415,
      created: (new Date((new Date())-1000*60*60*24*39))
    },
    {
      id: 2,
      ok: true,
      name: "Дещо інше",
      koef: 2.71828,
      created: (new Date((new Date())-1000*60*60*24*3))
    },
    {
      id: 3,
      ok: true,
      name: "Щось іще...",
      koef: -9.1,
      created: (new Date())
    },
    {
      id: 4,
      ok: false,
      name: "No",
      koef: 1,
      created: (new Date('0000-00-00'))
    },
    {
      id: 5,
      ok: false,
      name: "More",
      koef: 2,
      created: (new Date('0000-00-00'))
    },
    {
      id: 6,
      ok: false,
      name: "Heroes",
      koef: 3,
      created: (new Date('0000-00-00'))
    },
    {
      id: 7,
      ok: false,
      name: "Anymore",
      koef: 4,
      created: (new Date('0000-00-00'))
    },
    {
      id: 8,
      ok: true,
      name: "Відбій",
      koef: 5,
      created: (new Date())
    },
    {
      id: 9,
      ok: true,
      name: "Велика кількість символів 1234567890",
      koef: 111,
      created: null
    },
    {
      id: 10,
      ok: false,
      name: "",
      koef: 0,
      created: null
    }
  ]
};