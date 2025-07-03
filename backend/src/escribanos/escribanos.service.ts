import { Injectable } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, FindOptionsWhere, Like } from 'typeorm';
import { Escribano } from './escribano.entity';
import { CreateEscribanoDto } from './dto/create-escribano.dto';
import { UpdateEscribanoDto } from './dto/update-escribano.dto';

@Injectable()
export class EscribanosService {
  constructor(
    @InjectRepository(Escribano)
    private escribanoRepository: Repository<Escribano>,
  ) {}

  // Mostrar todos los escribanos activos, ordenados por nombre
  findAll(): Promise<Escribano[]> {
    return this.escribanoRepository.find({
      where: { activo: 1 },
      order: { nombre: 'ASC' },
    });
  }

  // Mostrar escribano por id
  findOne(id: number): Promise<Escribano | null> {
    return this.escribanoRepository.findOne({ where: { id, activo: 1 } });
  }

  // Mostrar escribanos filtrando por campo
  findByField(item: string, valor: string): Promise<Escribano | null> {
    const where: any = { activo: 1 };
    where[item] = valor;
    return this.escribanoRepository.findOne({ where });
  }

  // Mostrar escribanos inhabilitados
  findInhabilitados(): Promise<Escribano[]> {
    return this.escribanoRepository.find({
      where: { inhabilitado: 1 },
      order: { nombre: 'ASC' },
    });
  }

  // Mostrar solo id e inhabilitado de los activos
  findInhabilitadoEstado(): Promise<Partial<Escribano>[]> {
    return this.escribanoRepository.find({
      select: ['id', 'inhabilitado'],
      where: { activo: 1 },
      order: { nombre: 'ASC' },
    });
  }

  // Mostrar estado por campo
  findEstadoByField(item: string, valor: string): Promise<Partial<Escribano> | null> {
    const where: any = { activo: 1 };
    where[item] = valor;
    return this.escribanoRepository.findOne({
      select: ['id', 'inhabilitado'],
      where,
    });
  }

  // Crear escribano
  async create(createEscribanoDto: CreateEscribanoDto): Promise<Escribano> {
    const escribano = this.escribanoRepository.create(createEscribanoDto);
    return this.escribanoRepository.save(escribano);
  }

  // Editar escribano
  async update(id: number, updateEscribanoDto: UpdateEscribanoDto): Promise<string> {
    await this.escribanoRepository.update(id, updateEscribanoDto);
    return 'ok';
  }

  // Eliminar escribano
  async remove(id: number): Promise<string> {
    await this.escribanoRepository.delete(id);
    return 'ok';
  }
}
