import { Entity, PrimaryGeneratedColumn, Column } from 'typeorm';

@Entity('apostillas')
export class Apostilla {
  @PrimaryGeneratedColumn()
  id: number;

  @Column('int')
  idventa: number;

  @Column('int')
  haya: number;

  @Column('int')
  folio: number;

  @Column('text')
  descripcion: string;

  @Column('text')
  nombre: string;

  @Column('text')
  intervino: string;

  @Column('double')
  importe: number;
} 