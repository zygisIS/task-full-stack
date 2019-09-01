<template>
  <div>
    <button @click="checkJsonUsers()">Add Random User</button>
    <p v-if="jsonUsersEmpty">Sorry, there are no more random users to add.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      jsonUsersList: [],
      chosenNumber: null,
      chosenRandomUser: null,
      jsonUsersEmpty: false
    };
  },
  methods: {
    checkJsonUsers() {
      axios
        .post("/api/check-users", this.jsonUsersList)
        .then(response => this.addRandomUser(response.data));
    },
    addRandomUser(checkedList) {
      if (!checkedList.length) {
        this.jsonUsersEmpty = true;
      } else {
        this.chosenNumber = Math.floor(Math.random() * checkedList.length);
        this.chosenRandomUser = checkedList[this.chosenNumber];
        const formedForm = {
          name: this.chosenRandomUser.name,
          username: this.chosenRandomUser.username,
          email: this.chosenRandomUser.email
        };
        axios
          .post("/api/users", formedForm)
          .then(response => this.$emit("newUser", response.data.data));
      }
    },
    randomUsersList() {
      axios
        .get("https://jsonplaceholder.typicode.com/users")
        .then(response => (this.jsonUsersList = response.data));
    }
  },
  mounted() {
    this.randomUsersList();
  }
};
</script>

<style lang="scss" scoped>
@import "../../../sass/app.scss";

button {
  border-radius: $borders;
  text-decoration: none;
  text-transform: uppercase;
  background-color: color(accent);
  display: block;
  text-align: center;
  margin: 1em auto 2em auto;
  padding: $padding;
}

button:hover {
  background-color: color(accent-dark);
  cursor: pointer;
}

p {
  text-align: center;
}
</style>