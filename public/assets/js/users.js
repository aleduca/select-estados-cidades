import http from "./http";

async function getUsers() {
  try {
    const { data } = await http.get("/users");
    console.log(data);
  } catch (error) {
    console.log(error);
  }
}

getUsers();
