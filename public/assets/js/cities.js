import http from "./http";

export default function cities() {
  const states = document.querySelector("#states");
  const cities = document.querySelector("#cities");

  states.addEventListener("change", async function (event) {
    try {
      const { data } = await http.get("/api/cities", {
        params: {
          uf: event.target.value,
        },
      });

      cities.length = 0;
      cities.appendChild(new Option("Aguarde, carregando cidades"));

      setTimeout(() => {
        cities.length = 0;
        cities.appendChild(new Option("Escolha uma cidade"));
        data.forEach((city) => {
          cities.appendChild(new Option(city.nome, city.id));
        });
      }, 1000);

      cities.addEventListener("change", function () {
        const btn_submit = document.querySelector("#btn_submit");

        btn_submit.removeAttribute("disabled");
      });

      console.log(data);
    } catch (error) {
      console.log(error);
    }
  });
}
