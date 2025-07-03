import { Controller, Get, Post, Body, Patch, Param, Delete, Query } from '@nestjs/common';
import { EscribanosService } from './escribanos.service';
import { CreateEscribanoDto } from './dto/create-escribano.dto';
import { UpdateEscribanoDto } from './dto/update-escribano.dto';

@Controller('escribanos')
export class EscribanosController {
  constructor(private readonly escribanosService: EscribanosService) {}

  @Post()
  create(@Body() createEscribanoDto: CreateEscribanoDto) {
    return this.escribanosService.create(createEscribanoDto);
  }

  @Get()
  findAll(@Query('item') item?: string, @Query('valor') valor?: string) {
    if (item && valor) {
      return this.escribanosService.findByField(item, valor);
    }
    return this.escribanosService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.escribanosService.findOne(+id);
  }

  @Get('/inhabilitados')
  findInhabilitados() {
    return this.escribanosService.findInhabilitados();
  }

  @Get('/inhabilitado')
  findInhabilitadoEstado() {
    return this.escribanosService.findInhabilitadoEstado();
  }

  @Get('/estado')
  findEstadoByField(@Query('item') item: string, @Query('valor') valor: string) {
    return this.escribanosService.findEstadoByField(item, valor);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateEscribanoDto: UpdateEscribanoDto) {
    return this.escribanosService.update(+id, updateEscribanoDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.escribanosService.remove(+id);
  }
}
