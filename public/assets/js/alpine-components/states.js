import http from "../http";

export default function select() {
  return {
    states: [],
    state: {},
    cities: [],
    async getStates() {
      try {
        const { data } = await http.get("/api/states");
        this.states = data;
      } catch (error) {
        console.log(error);
      }
    },

    async getCities() {
      try {
        this.cities = [];
        const { data } = await http.get("/api/cities", {
          params: {
            uf: this.state,
          },
        });

        const cities = this.$refs.cities;

        cities.length = 0;
        cities.append(new Option("Aguarde, carregando cidades"));

        setTimeout(() => {
          cities.length = 0;
          cities.prepend(new Option("Escolha uma cidade"));
          this.cities = data;
        }, 1000);
      } catch (error) {
        console.log(error);
      }
    },

    citySelected() {
      this.$refs.submit.removeAttribute("disabled");
    },
  };
}
