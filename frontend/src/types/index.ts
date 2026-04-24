export interface Weather {
  temperature: number;
  unit: string;
  windspeed: number;
  weathercode: number;
}

export interface Sucursal {
  id?: number;
  nombre: string;
  ciudad: string;
  pais: string;
  latitud: number;
  longitud: number;
  clima?: Weather;
}

export interface Empleado {
  id?: number;
  nombre: string;
  email: string;
  sucursal_id: number;
}

export interface AuthState {
  user: any;
  token: string | null;
}
