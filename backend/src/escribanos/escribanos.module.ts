import { Module } from '@nestjs/common';
import { EscribanosService } from './escribanos.service';
import { EscribanosController } from './escribanos.controller';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Escribano } from './escribano.entity';

@Module({
  imports: [TypeOrmModule.forFeature([Escribano])],
  controllers: [EscribanosController],
  providers: [EscribanosService],
})
export class EscribanosModule {}
