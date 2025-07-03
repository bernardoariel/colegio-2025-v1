import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { TypeOrmModule } from '@nestjs/typeorm';
import { EscribanosModule } from './escribanos/escribanos.module';
import { ApostillasModule } from './apostillas/apostillas.module';

@Module({
  imports: [
    TypeOrmModule.forRoot({
      type: 'mysql',
      host: 'localhost',
      port: 3306,
      username: 'user',
      password: 'userpass',
      database: 'colegio',
      autoLoadEntities: true,
      synchronize: true,
      charset: 'utf8',
    }),
    EscribanosModule,
    ApostillasModule,
  ],
  controllers: [AppController],
  providers: [AppService],
})
export class AppModule {}
