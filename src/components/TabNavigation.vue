<script setup>
import { ref } from 'vue'

</script>

<template>
    <ul class="tab-menu">
        <li><router-link to="/">Store Settings</router-link></li>
        <li><router-link to="/hub">Hub Settings</router-link></li>
    </ul>
</template>


<style scoped>
.tab-menu {
  width: 100%;
  height: auto;
  margin: 30px 0 0 0;
  padding: 0;
}

.tab-menu li {
  display: inline-block;
  background-color: #fff;
}

.tab-menu li a {
  display: block;
  padding: 12px 25px;
  text-decoration: none;
  font-size: 16px;
  font-weight: 500;
  outline: 0;
}

.tab-menu li a.router-link-exact-active {
  background-color: #0073aa;
  color: #fff;
}

.tab-menu li a:focus,
.tab-menu li a:active {
  outline: 0;
  border: 0;
  box-shadow: none;
}

.tab-container {
  width: 96%;
  min-height: 400px;
  padding: 30px;
  background-color: #fff;
  margin-top: -5px;
}
</style>

