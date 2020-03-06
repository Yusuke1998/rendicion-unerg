<template>
  <v-app id="inspire">

    <v-navigation-drawer
      v-model="drawer"
      app
      clipped>
      <v-list dense>
        <v-list-item link :to="{ name: 'home' }">
          <v-list-item-action>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Inicio</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app clipped-left>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title>CONSULTA - ARC</v-toolbar-title>
    </v-app-bar>

    <v-content>
  		<v-container>
  			<transition name="fade">
  				<router-view/>
  			</transition>
  		</v-container>
  	</v-content>

    <v-footer app>
      <span>
        &copy; DIRECCIÓN DE INFORMATICA / UNERG / <a href="https://github.com/Yusuke1998/" target="_blank">YUSUKE1998</a> - 2020
      </span>
    </v-footer>
    
  </v-app>
</template>

<script>
  export default {
    data: () => ({
      drawer: null,
    })
  }
</script>