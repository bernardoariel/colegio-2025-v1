import { Injectable } from '@nestjs/common';
import { CreateApostillaDto } from './dto/create-apostilla.dto';
import { UpdateApostillaDto } from './dto/update-apostilla.dto';
import { InjectRepository } from '@nestjs/typeorm';
import { Apostilla } from './apostilla.entity';
import { Repository } from 'typeorm';

@Injectable()
export class ApostillasService {

  constructor(
    @InjectRepository(Apostilla)
    private apostillaRepository: Repository<Apostilla>
  ){}

  // Mostrar todas las apostillas
  findAll(): Promise<Apostilla[]> {
    return this.apostillaRepository.find();
  }

  // Mostrar apostilla por id
  findOne(id: number): Promise<Apostilla | null> {
    return this.apostillaRepository.findOne({ where: { id } });
  }

  // Mostrar apostilla por campo
  findByField(item: string, valor: string): Promise<Apostilla | null> {
    const where: any = {};
    where[item] = valor;
    return this.apostillaRepository.findOne({ where });
  }

  // Mostrar todas las apostillas de una venta
  findAllByVenta(idventa: number): Promise<Apostilla[]> {
    return this.apostillaRepository.find({ where: { idventa } });
  }

  // Mostrar apostilla como JSON por campo
  findJsonByField(item: string, valor: string): Promise<Apostilla | null> {
    const where: any = {};
    where[item] = valor;
    return this.apostillaRepository.findOne({ where });
  }

  // Actualizar datos de apostilla
  async update(id: number, updateApostillaDto: UpdateApostillaDto): Promise<string> {
    await this.apostillaRepository.update(id, updateApostillaDto);
    return 'ok';
  }

  // Crear apostilla
  async create(createApostillaDto: CreateApostillaDto): Promise<Apostilla> {
    const apostilla = this.apostillaRepository.create(createApostillaDto);
    return this.apostillaRepository.save(apostilla);
  }

  // Eliminar apostilla
  async remove(id: number): Promise<string> {
    await this.apostillaRepository.delete(id);
    return 'ok';
  }
}
