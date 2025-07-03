import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('escribanos')
export class Escribano {
  @PrimaryGeneratedColumn()
  id: number;

  @Column('text')
  nombre: string;

  @Column('text')
  direccion: string;

  @Column('text')
  localidad: string;

  @Column('text')
  telefono: string;

  @Column('int')
  documento: number;

  @Column('int')
  id_tipo_iva: number;

  @Column('text')
  tipo: string;

  @Column('text')
  facturacion: string;

  @Column('text')
  tipo_factura: string;

  @Column('varchar', { length: 30 })
  cuit: string;

  @Column('text')
  email: string;

  @Column('int', { default: 1 })
  id_categoria: number;

  @Column('int', { default: 0 })
  id_escribano_relacionado: number;

  @Column('int', { default: 0 })
  id_osde: number;

  @Column('int', { default: 0 })
  ultimolibrocomprado: number;

  @Column('int', { default: 0 })
  ultimolibrodevuelto: number;

  @Column('int', { default: 0 })
  inhabilitado: number;

  @Column('text')
  obs: string;

  @Column('int', { default: 1 })
  activo: number;

  @Column('text')
  obsdel: string;

  @Column('timestamp', { default: () => 'CURRENT_TIMESTAMP' })
  fechacreacion: Date;

  @Column('text')
  apellido_ws: string;

  @Column('text')
  nombre_ws: string;

  @Column('int')
  matricula_ws: number;
} 