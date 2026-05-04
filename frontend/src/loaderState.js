import { ref } from 'vue';

export const cargandoGlobal = ref(false);

export function mostrarLoader() {
  cargandoGlobal.value = true;
}

export function ocultarLoader() {
  setTimeout(() => {
    cargandoGlobal.value = false;
  }, 500);
}